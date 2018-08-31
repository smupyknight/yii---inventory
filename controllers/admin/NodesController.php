<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : NodesController.php
# Created on : 20th Aug 2015 by Bakhtawar Khan
# Update on : 28th Oct 2015 by Bakhtawar Khan
# Purpose : This page will perform CRUD on parent & child nodes.
*/
namespace app\controllers\admin;

use Yii;
use app\models\LocationNodes;
use app\models\SearchLocationNodes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\Expression;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;
use app\models\SurveyTemplateQuestions;
use app\models\SurveyTemplateQuestionNodes;
use app\models\Audits;

/**
 * NodesController implements the CRUD actions for LocationNodes model.
 */
class NodesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
			 ],
			 'access' => [
                'class' => AccessControl::className(),
				'ruleConfig' => [
        				'class' => AccessRule::className(),
    			],
                'only' => ['view', 'create', 'index', 'update', 'delete', 'updateposition', 'childnodes', 'createchild','updatechild'],
                'rules' => [
                   [
                        'actions' => ['view', 'index', 'update', 'delete', 'create', 'updateposition', 'childnodes', 'createchild', 'updatechild'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all LocationNodes models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new SearchLocationNodes();
		$intPropId = 0;
		## Get property Types for filter( calling getter method getPropertyTypes )
		$arrPropertyTypes = $searchModel->PropertyTypes;
		
		## Get Posted data i.e Property type selected by user
		$arrPostedData = Yii::$app->request->post('SearchLocationNodes');
		if(!empty($arrPostedData)) {
			$intPropId = $arrPostedData['property_type_id'];
		}else if(!empty($arrPropertyTypes)) {
			$intPropId = $arrPropertyTypes[0]['id'];
		}
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $intPropId);
		//$dataProvider->pagination->pageSize=5;
		$arrPropertyTypes = ArrayHelper::map($arrPropertyTypes,'id','name');
		$searchModel->property_type_id = $intPropId;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'propertyTypes' => $arrPropertyTypes,
			
		]);
    }

    

    /**
     * Creates a new LocationNodes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = '')
    {
		$param = $id;
		$session = Yii::$app->session;
		## Check whether Param is blank 
		if($param == "" || !is_numeric($param)){
			$session->setFlash('error', NODE_PARAM_ERR);
			return $this->redirect(['index']);
		}
        $model = new LocationNodes();
		## Set Scenario
		$model->scenario = 'create';
		## Get Property TYpe Name
		$model->propertyName = $model->getPropertyTypeName($param);
		$model->property_type_id = $param;
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			## Set default values
			$model->created_at = new Expression('NOW()');
			$model->updated_at = new Expression('NOW()');
			$model->status = 'Active';
			## GEt Position for new record
			$model->position = $model->getcurrentPosition($model->property_type_id);
			 if ($model->save()) { 
			 	 $intLastinsertedId = $model->id;
			 	 /**If new node is added under a property type,check if this property is assigned 
			 	    to any question, is yes, new node will be added to that question as well.
			 	 **/
			 	 $templateQuestion = SurveyTemplateQuestions::find()
			 	 					->select('id')
			 	 					->where(['property_type_id' => $param])
			 	 					->asArray()
			 	 					->all();
			 	 if(!empty($templateQuestion)){
			 	 	## insert new location node for all questions
			 	 	$connection = Yii::$app->db;
			 	 	$insertRow = [];
			 	 	$intVersion = 1;
			 	 	for($i=0; $i<count($templateQuestion); $i++) {
			 	 		/*$insertRow = [
			 	 			'name' => $model->name,
			 	 			'included' => '1',
			 	 			'capture_level' => '1',
			 	 			'location_node_id' => $intLastinsertedId,
			 	 			'survey_template_question_node_id' => 'NULL',
			 	 			'survey_template_question_id' => $templateQuestion[$i]['id']
			 	 		];
			 	 	   $out = "---";
						foreach($insertRow as $k => $v) {
						    $out .= "$k:$v\n";
						}
						$out = substr($out, 0, -1);*/
						## Create string to store in a format used by ROR developers
					$insertRow = "---name :".$model->name."\nincluded :1\ncapture_level :1\nlocation_node_id :".$intLastinsertedId."\nsurvey_template_question_node_id :\nsurvey_template_question_id :".$templateQuestion[$i]['id'];
			 	 	//echo '<pre/>';echo($insertRow);exit;
			 	 	$boolStatus=$connection ->createCommand()
 							   ->insert('survey_template_question_nodes',
 							   	[
	 							   	'name' => $model->name,
					 	 			'included' => '1',
					 	 			'capture_level' => '1',
					 	 			'location_node_id' => $intLastinsertedId,
					 	 			'survey_template_question_node_id' => '',
					 	 			'survey_template_question_id' => $templateQuestion[$i]['id']
 							   	])
							   ->execute();
					$intAuditableId = Yii::$app->db->getLastInsertID();
				    
					## If inserted successfully , add it to audit table
					if($boolStatus) {
						$maxVersionArr = Audits::find()->select('max(version) as max_version')
										 ->where([
										 	'auditable_id' => $intAuditableId,
										 	'auditable_type' => 'SurveyTemplateQuestionNode'
										 	 ])
										 ->asArray()
										 ->One();
						if($maxVersionArr['max_version'] != '') {
							$intVersion = $maxVersionArr['max_version'] + 1;
						} 
						$connection->createCommand()->insert('audits', [
					    	'auditable_type' => 'SurveyTemplateQuestionNode',
					    	'created_at' => new Expression('NOW()'),
					    	'user_type' => 'User',
					    	'action' => 'create',
					    	'version' => $intVersion,
					    	'username' => '',
					    	'auditable_id' => $intAuditableId,
					    	'user_id' => Yii::$app->user->identity->id,
					    	'changes' => $insertRow
						])
						->execute();
					}
					}
			 	 }
				 $session->setFlash('success', NODE_ADD_SUCC);
            } else {
				$session->setFlash('error', NODE_ADD_ERR);
			}
         return $this->redirect(['index']);
        }
		return $this->render('create', [
			'model' => $model,
		]);
        
    }

    /**
     * Updates an existing LocationNodes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldLocationName = $model->name;
		$session = Yii::$app->session;
		## Set Scenario
		$model->scenario = 'update';
		$currStatus = $model->status;
		## Get Property TYpe Name
		$model->propertyName = $model->getPropertyTypeName($model->property_type_id);
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			$model->updated_at = new Expression('NOW()');
			if($model->status == '') {
				$model->status = $currStatus;
			}
			if ($model->save()) {
				
				## If location name is updated, then only we needto update other tables.
				if($oldLocationName != $model->name) {
					 ## If location name is updated,update it for contributor also
					 $connection = Yii::$app->db;
					 $boolStatus = $connection ->createCommand()
	 							->update('contributor_nodes', 
	 								['name' => $model->name], 
	 								'location_node_id = '.$model->id)
								->execute();

					 ## Also update survey question nodes
					 $boolLocStatus = $connection ->createCommand()
	 							->update('survey_template_question_nodes', 
	 								['name' => $model->name], 
	 								'location_node_id = '.$model->id)
								->execute();
					if($boolLocStatus >0) {	

					$questionModel =SurveyTemplateQuestionNodes::find()
									->select('id')
									->where(['location_node_id' => $model->id])
									->asArray()
									->all();
					$nodeArr= [];
					foreach($questionModel as $k=>$v) {
						$nodeArr[] = $v['id'];
					}
					//print_r($nodeArr);				
					 ## Add this updation in audit trail
					$maxVersionArr = Audits::find()->select('max(version) as max_version, auditable_id')
										 ->where([
										 	'auditable_id' => $nodeArr,
										 	])
										 ->andWhere(['auditable_type' => 'SurveyTemplateQuestionNode'])
										 ->groupBy('auditable_id')
										 ->asArray()
										 ->all();
										 
										 //echo $maxVersionArr->createCommand()->getRawSql();
					 $versionArr = [];
					if(count($maxVersionArr)>0) {
						foreach($maxVersionArr as $k=>$v) {
							$versionArr[$v['auditable_id']] = $v['max_version'];
						}
					}
					$strChanges = '--- name :'.$model->name;
					for($i=0; $i<count($nodeArr); $i++) {
						if(isset($versionArr[$nodeArr[$i]])) {
							$intVersion = $versionArr[$nodeArr[$i]] + 1;
						} else {
							$intVersion = 1;
						}
			 	 		$insertRow[] = [
			 	 			'auditable_type' => 'SurveyTemplateQuestionNode',
					    	'created_at' => new Expression('NOW()'),
					    	'user_type' => 'User',
					    	'action' => 'update',
					    	'version' => $intVersion,
					    	'username' => '',
					    	'auditable_id' => $nodeArr[$i],
					    	'user_id' => Yii::$app->user->identity->id,
					    	'changes' => $strChanges
			 	 		];
			 	 	}
										 
						$connection->createCommand()->batchInsert('audits', [
					    	'auditable_type' ,
					    	'created_at',
					    	'user_type',
					    	'action',
					    	'version' ,
					    	'username',
					    	'auditable_id',
					    	'user_id' ,
					    	'changes'
						], $insertRow)
						->execute();
					}
				}		
				 $session->setFlash('success', NODE_UPDATE_SUCC);
            } else {
				$session->setFlash('error', NODE_UPDATE_ERR);
			}
			return $this->redirect(['index']);
		}
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LocationNodes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
		$session = Yii::$app->session;
		$intId = Yii::$app->request->post('id');
		$isDeleted = $this->findModel($intId)->delete();
        if($isDeleted) {
			$session->setFlash('success', NODE_DEL_SUCC);
		} else {
			$session->setFlash('error', NODE_DEL_ERR);
		}
		return $this->redirect(Yii::$app->request->referrer);
		//return $this->redirect(['index']);
    }

    /**
     * Finds the LocationNodes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocationNodes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocationNodes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requesddddted page does not exist.');
        }
    }
	
	/**
	* UPdate Positon of nodes
	*/
	public function actionUpdateposition()
	{
		$arrPositions = Yii::$app->request->post('position');
		$intCurrPage  = Yii::$app->request->post('page');
		$intPerPage  = Yii::$app->request->post('per_page');
		if($intCurrPage != 0) {
			$intCount = (($intCurrPage * $intPerPage) - $intPerPage) + 1;
		} else {
			$intCount = 1;
		}
		
		$arrOutput = LocationNodes::setChangeSequence($arrPositions, $intCount);
		return $arrOutput;
	}
	
	/**
	*  Listing of child nodes
	**/
	public function actionChildnodes($parent)
	{
		$searchModel = new SearchLocationNodes();
		
        $dataProvider = $searchModel->searchChildNodes(Yii::$app->request->queryParams, $parent);
		//$dataProvider->pagination->pageSize=5;
		## Get Property Name
		$arrData = $dataProvider->getModels();
		if(!empty($arrData)) {
			$intPropId = $arrData[0]->property_type_id;
		}else {
			$objParent = $this->findModel($parent);
			$intPropId = $objParent->property_type_id;
		}
		$strpropertyName = $searchModel->getPropertyTypeName($intPropId);
		## Get Parent Name
		$intParentId = isset($arrData[0]->location_node_id) ? $arrData[0]->location_node_id : $parent;
		$strparentName = $searchModel->getParentName($intParentId);
        return $this->render('child-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'propertyName' => $strpropertyName,
			'parentName' => $strparentName,
			'propertyId' => $intPropId,
			'parentId' => $intParentId,
		]);
	}
	
	/**
	* Create Child Node
	**/
	public function actionCreatechild($prop, $parent)
	{
		$session = Yii::$app->session;
		## Check whether Param is blank 
		if($prop == "" || $parent == ""){
			$session->setFlash('error', NODE_PARAM_ERR);
			return $this->redirect(['childnodes']);
		}
        $model = new LocationNodes();
		## Set Scenario
		$model->scenario = 'createChild';
		## Get Property TYpe Name
		$model->propertyName = $model->getPropertyTypeName($prop);
		$model->parentName = $model->getParentName($parent);
		$model->property_type_id = $prop;
		$model->location_node_id = $parent;
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			## Set default values
			$model->created_at = new Expression('NOW()');
			$model->updated_at = new Expression('NOW()');
			$model->status = 'Active';
			## Get Position for new record
			$model->position = $model->getcurrentPosition($model->property_type_id, $model->location_node_id);
			
			if ($model->save()) {
				 $intLastinsertedId = $model->id;
			 	 /**If new node is added under a property type,check if this property is assigned 
			 	    to any question, is yes, new node will be added to that question as well.
			 	 **/
			 	 $templateQuestion = SurveyTemplateQuestions::find()
			 	 					->select('id')
			 	 					->where(['property_type_id' => $model->property_type_id])
			 	 					->asArray()
			 	 					->all();
			 	 if(!empty($templateQuestion)){
			 	 	## insert new location node for all questions
			 	 	$connection = Yii::$app->db;
			 	 	$insertRow = [];
			 	 	$intVersion = 1;
			 	 	for($i=0; $i<count($templateQuestion); $i++) {
			 	 		## Create string to store in a format used by ROR developers
					$insertRow = "---name :".$model->name."\nincluded :1\ncapture_level :0\nlocation_node_id :".$intLastinsertedId."\nsurvey_template_question_node_id :".$model->location_node_id."\nsurvey_template_question_id :".$templateQuestion[$i]['id'];
			 	 	$boolStatus=$connection ->createCommand()
 							   ->insert('survey_template_question_nodes',
 							   	[
	 							   	'name' => $model->name,
					 	 			'included' => '1',
					 	 			'capture_level' => '0',
					 	 			'location_node_id' => $intLastinsertedId,
					 	 			'survey_template_question_node_id' => $model->location_node_id,
					 	 			'survey_template_question_id' => $templateQuestion[$i]['id']
 							   	])
							   ->execute();
					$intAuditableId = Yii::$app->db->getLastInsertID();
				    
					## If inserted successfully , add it to audit table
					if($boolStatus) {
						$maxVersionArr = Audits::find()->select('max(version) as max_version')
										 ->where([
										 	'auditable_id' => $intAuditableId,
										 	'auditable_type' => 'SurveyTemplateQuestionNode'
										 	 ])
										 ->asArray()
										 ->One();
						if($maxVersionArr['max_version'] != '') {
							$intVersion = $maxVersionArr['max_version'] + 1;
						} 
						$connection->createCommand()->insert('audits', [
					    	'auditable_type' => 'SurveyTemplateQuestionNode',
					    	'created_at' => new Expression('NOW()'),
					    	'user_type' => 'User',
					    	'action' => 'create',
					    	'version' => $intVersion,
					    	'username' => '',
					    	'auditable_id' => $intAuditableId,
					    	'user_id' => Yii::$app->user->identity->id,
					    	'changes' => $insertRow
						])
						->execute();
					}
					} 
				}
				 $session->setFlash('success', CHILD_NODE_ADD_SUCC);
            } else {
				$session->setFlash('error', CHILD_NODE_ADD_ERR);
			}
         return $this->redirect(['childnodes', 'parent' => $model->location_node_id]);
        	
		}
		return $this->render('create-child', [
			'model' => $model,
			'intParentId' => $parent
		]);
	}
	
	/**
	* Update Child Node
	**/
	public function actionUpdatechild($id)
	{
		$session = Yii::$app->session;
		$model = $this->findModel($id);
		$currStatus = $model->status;
		$oldLocationName = $model->name;
		## Set Scenario
		$model->scenario = 'updateChild';
		## Get Property TYpe Name
		$model->propertyName = $model->getPropertyTypeName($model->property_type_id);
		$model->parentName = $model->getParentName($model->location_node_id);
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			## Set default values
			$model->updated_at = new Expression('NOW()');
			if($model->status == '') {
				$model->status = $currStatus;
			}
			if ($model->save()) {
			     ## If location name is updated, then only we needto update other tables.
				if($oldLocationName != $model->name) {
					 ## If location name is updated,update it for contributor also
					 $connection = Yii::$app->db;
					 $boolStatus = $connection ->createCommand()
	 							->update('contributor_nodes', 
	 								['name' => $model->name], 
	 								'location_node_id = '.$model->id)
								->execute();

					 ## Also update survey question nodes
					 $boolLocStatus = $connection ->createCommand()
	 							->update('survey_template_question_nodes', 
	 								['name' => $model->name], 
	 								'location_node_id = '.$model->id)
								->execute();
					if($boolLocStatus >0) {	

					$questionModel =SurveyTemplateQuestionNodes::find()
									->select('id')
									->where(['location_node_id' => $model->id])
									->asArray()
									->all();
					$nodeArr= [];
					foreach($questionModel as $k=>$v) {
						$nodeArr[] = $v['id'];
					}
					//print_r($nodeArr);				
					 ## Add this updation in audit trail
					$maxVersionArr = Audits::find()->select('max(version) as max_version, auditable_id')
										 ->where([
										 	'auditable_id' => $nodeArr,
										 	])
										 ->andWhere(['auditable_type' => 'SurveyTemplateQuestionNode'])
										 ->groupBy('auditable_id')
										 ->asArray()
										 ->all();
										 
										 //echo $maxVersionArr->createCommand()->getRawSql();
					 $versionArr = [];
					if(count($maxVersionArr)>0) {
						foreach($maxVersionArr as $k=>$v) {
							$versionArr[$v['auditable_id']] = $v['max_version'];
						}
					}
					$strChanges = '--- name :'.$model->name;
					for($i=0; $i<count($nodeArr); $i++) {
						if(isset($versionArr[$nodeArr[$i]])) {
							$intVersion = $versionArr[$nodeArr[$i]] + 1;
						} else {
							$intVersion = 1;
						}
			 	 		$insertRow[] = [
			 	 			'auditable_type' => 'SurveyTemplateQuestionNode',
					    	'created_at' => new Expression('NOW()'),
					    	'user_type' => 'User',
					    	'action' => 'update',
					    	'version' => $intVersion,
					    	'username' => '',
					    	'auditable_id' => $nodeArr[$i],
					    	'user_id' => Yii::$app->user->identity->id,
					    	'changes' => $strChanges
			 	 		];
			 	 	}
										 
						$connection->createCommand()->batchInsert('audits', [
					    	'auditable_type' ,
					    	'created_at',
					    	'user_type',
					    	'action',
					    	'version' ,
					    	'username',
					    	'auditable_id',
					    	'user_id' ,
					    	'changes'
						], $insertRow)
						->execute();
					}
				}	 
				 $session->setFlash('success', CHILD_NODE_UPDATE_SUCC);
            } else {
				$session->setFlash('error', CHILD_NODE_UPDATE_ERR);
			}
         return $this->redirect(['childnodes', 'parent' => $model->location_node_id]);
        	
		}
		return $this->render('update-child', [
			'model' => $model,
			'intParentId' => $model->location_node_id
		]);
	}
}
