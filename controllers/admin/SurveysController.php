<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : SurveysController.php
# Created on : 20th Aug 2015 by Bakhtawar Khan
# Update on : 22th Aug 2015 by Bakhtawar Khan
# Purpose : This page will perform CRUD on surveys,node categories.
*/
namespace app\controllers\admin;

use Yii;
use app\models\SurveyTemplates;
use app\models\SurveyTemplatesSearch;
use app\models\SurveySearch;
use app\models\TempDistribution;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Contributors;
use app\models\SurveyQuarters;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\components\AccessRule;
use app\models\User;
use app\models\SurveyTemplateQuestionsSearch;
use app\models\SurveyTemplateQuestions;
use app\models\SurveyTemplateNodeCategoriesSearch;
use app\models\SurveyTemplateNodeCategories;
use app\models\Audits;
use app\models\AuditSearch;
use app\models\PropertyTypes;
use app\models\LocationNodes;
use app\models\SurveyTemplateCategories;
use app\models\SurveyTemplateQuestionNodes;
use app\models\DataFieldTemplates;
use app\models\NodeFieldExclusions;
use app\models\Contributorsserach;
use yii\db\Expression;
use app\models\OutputTables;
use yii\helpers\ArrayHelper;
use app\models\Surveys;
use app\models\OutputTableColumnHeadings;
use app\models\DataFields;
use app\models\FieldFilters;
use dosamigos\tableexport\ButtonTableExport;
//use dosamigos\tableexport\TableExportAction;


/**
 * SurveysController implements the CRUD actions for SurveyTemplates model.
 */
class SurveysController extends Controller
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
                'only' => ['view', 'create', 'index', 'update', 'delete', 'contributors',
				'sendnotificationmail', 'close', 'updatequestion', 'addquestion', 'questiondetails',
				'nodecategories', 'addnode', 'audit_trail', 'walk', 'deletecolumn', 'checkexclusion', 'changeincluded',
				'distributesurvey','outputtables','calculations','contribution','getabrvalues','deletesv'],
                'rules' => [
                   [
                        'actions' => ['view', 'index', 'update', 'delete', 'create', 'contributors',
						'sendnotificationmail', 'close', 'updatequestion', 'addquestion', 'questiondetails',
						'nodecategories', 'addnode', 'audit_trail', 'walk',  'deletecolumn', 'checkexclusion', 'changeincluded',
						'distributesurvey','outputtables','calculations','contribution','getabrvalues','deletesv'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                 ],


            ],


        ];
    }

    public function actions()
	{
    	return [
        	// ...
        	'download' => [
            	//'class' => TableExportAction::className()
        	]
        // ...
    	];
	}

    /**
     * Lists all SurveyTemplates models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new SurveyTemplatesSearch();
		## Fetch Values for Filter Dropdown
		$arrQuarters = $searchModel->getQuartersList();
		$arrPublications = $searchModel->getPublicationList();
		if(isset(Yii::$app->request->queryParams['SurveyTemplatesSearch']['quarter'])) {
			$searchQuarter = Yii::$app->request->queryParams['SurveyTemplatesSearch']['quarter'];
		} else {
			$searchQuarter = array_values($arrQuarters)[0];
		}
		//if($_SERVER["REMOTE_ADDR"]=='195.168.77.18'){ echo '<pre/>';print_r(Yii::$app->request->queryParams); }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $searchQuarter);
        //$dataProvider->pagination->pagesize =2;
		$searchModel->quarter = $searchQuarter;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'quarters' => $arrQuarters,
			'publications' => $arrPublications
		]);
    }

    /**
     * Displays a single SurveyTemplates model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SurveyTemplates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new SurveyTemplates();
		$arrCatList = $model->getCategoryList();
		$arrPublications = $model->getPublicationList();
		$arrContList = $model->getContrTypeList();
		$model->scenario = 'create';
		$session = Yii::$app->session;
		if($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				$insertRow = "--- \ncontributor_type: ".$model->contributor_type."\nname: ".$model->name."\npublication: ".$model->publication."\nsurvey_template_category_id: ".$model->survey_template_category_id;
				## Find Current Quarter
				$quarter =floor((date('n') - 1) / 3) + 1;
				$currQuarter = date('Y').':'.$quarter;

        		## Add current quarter in survey quarter
				$quarterModel = new SurveyQuarters();
				$quarterModel->survey_template_id = $model->id;
				$quarterModel->quarter =$currQuarter;
				$quarterModel->distributed = 0;
				$quarterModel->created_at = new Expression('NOW()');
				$quarterModel->updated_at = new Expression('NOW()');
				$intVersion = 1;
				if($quarterModel->save()) {
					## Once the survey is created, you need to add it to audit table
					$maxVersionArr = Audits::find()->select('max(version) as max_version')
										 ->where([
										 	'auditable_id' => $model->id,
										 	'auditable_type' => 'SurveyTemplate'
										 	 ])
										 ->asArray()
										 ->One();
						if($maxVersionArr['max_version'] != '') {
							$intVersion = $maxVersionArr['max_version'] + 1;
						}
						$connection = Yii::$app->db;
						$connection->createCommand()->insert('audits', [
					    	'auditable_type' => 'SurveyTemplate',
					    	'created_at' => new Expression('NOW()'),
					    	'user_type' => 'User',
					    	'action' => 'create',
					    	'version' => $intVersion,
					    	'username' => '',
					    	'auditable_id' => $model->id,
					    	'user_id' => Yii::$app->user->identity->id,
					    	'changes' => $insertRow
						])
						->execute();
					$session->setFlash('success', SURVEY_ADD_SUCC);
				} else {
					$session->setFlash('error', SURVEY_ADD_ERR);
				}
			} else {
				$session->setFlash('error', SURVEY_ADD_ERR);
			}
		return $this->redirect(['index']);
		}
		return $this->render('create', [
                'model' => $model,
				'arrCatList' => $arrCatList,
				'arrPublications' => $arrPublications,
				'arrContList' => $arrContList
         ]);
    }

    /**
     * Updates an existing SurveyTemplates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        ## Convert model obj to array
        $modelArr = (array) $model->attributes;
        $arrCatList = $model->getCategoryList();
		$arrPublications = $model->getPublicationList();
		$arrContList = $model->getContrTypeList();
		$model->scenario = 'update';
		$session = Yii::$app->session;

        if($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				## update survey quarter info
				$quarterModel = new SurveyQuarters();
				$quarterModel->updated_at = new Expression('NOW()');

				if($quarterModel->save()) {

					## Once the survey is updated, you need to add it to audit table,written in aftersave().

					$session->setFlash('success', SURVEY_UPDATE_SUCC);
				} else {
					$session->setFlash('error', SURVEY_UPDATE_ERR);
				}
			} else {
				$session->setFlash('error', SURVEY_UPDATE_ERR);
			}
		return $this->redirect(['index']);
		}
		return $this->render('update', [
                'model' => $model,
				'arrCatList' => $arrCatList,
				'arrPublications' => $arrPublications,
				'arrContList' => $arrContList
         ]);
    }

    ## Delete Survey Question
    public function actionDelete()
    {
        //$this->findModel($id)->delete();
		$intId = Yii::$app->request->post('id');
		$session = Yii::$app->session;
		$model = SurveyTemplateQuestions::find()
				->where(['id' => $intId])
				->one();
		$intSurveyId = $model->survey_template_id;
		if($model->delete()) {
			## If question is deleted , all nodes related to this will be deleted.
			SurveyTemplateQuestionNodes::deleteAll('survey_template_question_id = :id', [':id' => $intId]);
			$session->setFlash('success', QUEST_DEL_SUCC);
		} else {
			$session->setFlash('error', QUEST_DEL_ERR);
			//print_r($model->getErrors());exit;
		}
       // return $this->redirect(['questions', 'id' => $intSurveyId]);
		return $this->redirect(Yii::$app->request->referrer);
    }

    ## Delete Survey and all questions
    public function actionDeletesv($id)
    {
        //$this->findModel($id)->delete();
		$session = Yii::$app->session;
		$model = SurveyTemplates::find()
				->where(['id' => $id])
				->one();
		if($model->delete()) {
			## If survey is deleted , all questions related to this will be deleted.
			SurveyTemplateQuestions::deleteAll('survey_template_id = :id', [':id' => $id]);
			## If survey is deleted , all nodes related to this will be deleted.
			#SurveyTemplateQuestionNodes::deleteAll('survey_template_question_id = :qid', [':qid' => $qid]);
			# TRIGGER IN DB
			$session->setFlash('success', SURVEY_DEL_SUCC);
		} else {
			$session->setFlash('error', SURVEY_DEL_ERR);
			//print_r($model->getErrors());exit;
		}
        // return $this->redirect(['questions', 'id' => $intSurveyId]);
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the SurveyTemplates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SurveyTemplates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SurveyTemplates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	##--------Contributors List of each quarter----##
	public function actionContributors($id, $quarter_id)
	{
		//$model= $this->findModel($id);
		$intQuarterId = $quarter_id;
		$surveyDetail = SurveyTemplates::fetchsurveyDetail($intQuarterId);
		$isDistributed = 0;
		if(isset($surveyDetail->surveyQuarter->distributed) && $surveyDetail->surveyQuarter->distributed == '1') {
			$isDistributed = 1;
		}
		$strContributorType = '';
		if(!empty($surveyDetail)) {
			$strContributorType = $surveyDetail->contributor_type;
		}
		## Fetch Question Nodes
		$surveyQuestionNodes = json_decode(SurveyTemplateQuestions::getQuestionNodes($id));
		//echo '<pre/>';print_r($surveyQuestionNodes);exit;
		 $contributorList = [];
		 $searchModel = new Contributorsserach();
		if(!empty($surveyQuestionNodes)) {
				$dataProvider = $searchModel->searchSurveyContributor(Yii::$app->request->queryParams, $surveyQuestionNodes, $strContributorType, $quarter_id, $isDistributed);
		} else {
			   $dataProvider = $searchModel->searchSurveyContributor(Yii::$app->request->queryParams, '',$strContributorType, $quarter_id, $isDistributed);
		} //print_r([Yii::$app->request->queryParams,$surveyQuestionNodes,$strContributorType,$quarter_id,$isDistributed]); die;
		//$searchModel = new SurveySearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $intQuarterId);
		//$dataProvider->pagination->pagesize =2;
		//$searchModel->closed = '1';
		//echo '<pre/>';$dataProvider->getModels();exit;
		return $this->render('contributor-index',[
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'intQuarterId' => $intQuarterId,
			'surveyDetail' => $surveyDetail,
		]);
	}

	##----------Send Notification Email ------##
	public function actionSendnotificationmail()
	{
		$intContributorId = Yii::$app->request->post('contributor_id');
		$intSurveyId = Yii::$app->request->post('survey_id');
		$intQuarterId = Yii::$app->request->post('quarter_id');
		$model = new Contributors();
		$ContributorDetail = $model->getContributorDetails($intContributorId);
		$to = $ContributorDetail['email'];
		//$to = 'rastislav@wallit.eu';
		$from = Yii::$app->params['adminEmail'];
		$surveyDetail = SurveyTemplates::find()
				->select(['survey_templates.*','deadline','survey_quarters.id as quarter_id'])
				->where(['survey_templates.id'=>$intSurveyId])
				->andWhere(['survey_quarters.id'=>$intQuarterId])
				->joinWith('surveyQuarter')
				->one();//print_r($surveyDetail);exit;
		$params = [
			'name' => $ContributorDetail['firstname'].' '.$ContributorDetail['lastname'],
			'survey' => $surveyDetail['name'],
			'deadline' => date('d M,Y', strtotime($surveyDetail['surveyQuarter']['deadline'])),
			'site_url' => Yii::$app->request->hostInfo.Yii::$app->request->baseUrl.'/contributor/questions/index/'.$intQuarterId
		];
		$boolStatus = Yii::$app->customcomponent->sendEmail($from, $to, NOTIFY_EMAIL , 'notification_email', $params);
		if($boolStatus) {
			return json_encode(['status' => 'success', 'message' => EMAIL_SENT_SUCC]);
		} else {
			return json_encode(['status' => 'error', 'message' => EMAIL_SENT_ERR]);
		}
	}

	## ------Add id of contributor in a temp table & send email though cron------##
	public function actionNotifycontributor()
	{
		$selection   = Yii::$app->request->post('selection');
		$intSurveyId = Yii::$app->request->post('survey_id');
		$insertRow = [];

		$model = new Contributors();

		for($i=0; $i<count($selection); $i++) {
			$ContributorDetail = $model->getContributorDetails($selection[$i]);
			if ($ContributorDetail['disabled']!='2') {
				$insertRow[] = [
    				'contributor_id' => $selection[$i],
					'survey_id' => $intSurveyId,
    			];
			}
		}
		$connection = Yii::$app->db;
		$boolStatus=$connection ->createCommand()
 			->batchInsert('temp_notification_mail',['contributor_id', 'survey_id'], $insertRow)
			->execute();
		$session = Yii::$app->session;
		if($boolStatus == true) {
			$session->setFlash('success', EMAIL_QUEUED_SUCC);
		} else {
			$session->setFlash('success', EMAIL_QUEUED_ERR);
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	## ------- SEt Deadline -------- ##
	public function actionSetdeadline()
	{
    if(!empty(Yii::$app->request->post('selection'))){
      $session = Yii::$app->session;
      $deadline = Yii::$app->request->post('deadline');
      if(empty($deadline)){
        $session->setFlash('error', 'Deadline not set');
      }else{
        $ids = Yii::$app->request->post('selection');
        $quarter_txt = Yii::$app->request->post('curr_quarter');
        $return  = [];
        foreach ($ids  as $key => $quarter) {
          $model = SurveyQuarters::find()->where(['survey_template_id' => $quarter, 'quarter' => $quarter_txt])->one();
          $model->deadline = $deadline;
          $return[] = $model->update();
        }
        $session->setFlash('success', DEADLINE_SUCC);
      }
      return $this->redirect(Yii::$app->request->referrer);
    }else{
  		$intQuarterId = Yii::$app->request->post('quarterId');
  		$deadline = Yii::$app->request->post('deadline');

  		$model = SurveyQuarters::find()->where(['id' => $intQuarterId])->one();
  		## ajax call performed to check unique name
  			if (Yii::$app->request->isAjax) {
      		    Yii::$app->response->format = Response::FORMAT_JSON;
         		    return ActiveForm::validate($model);
  			}
  		$model->deadline = $deadline;
  		$session = Yii::$app->session;
  		if($model->update()) {
  			$session->setFlash('success', DEADLINE_SUCC);
  		} else {
  			$session->setFlash('error', DEADLINE_ERR);
  		}
    }
		return $this->redirect(Yii::$app->request->referrer);

	}

	##---- close survey-----##
	public function actionClose()
	{
		$intQuarterId = Yii::$app->request->post('quartId');
		$model = SurveyQuarters::find()->where(['id' => $intQuarterId])->one();
		$model->updated_at = new Expression('NOW()');
		$model->closed = '1';
		$session = Yii::$app->session;

		if($model->update()) {
			## If survey is closed, it will be opened in next survey

			$surveyModel = new SurveyQuarters();
			$nextQuarter = $surveyModel->getNextQuarter($model->quarter);
			$surveyModel->survey_template_id = $model->survey_template_id;
			$surveyModel->quarter = $nextQuarter;
			$surveyModel->updated_at = $surveyModel->created_at = new Expression('NOW()');

			if($surveyModel->save()) {
				$session->setFlash('success', SURVEY_CLOSE_SUCC);
			} else {
				//print_r($surveyModel->getErrors());exit;
			}
		} else {
			$session->setFlash('error', SURVEY_CLOSE_ERR);
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	##---- Survey Questions ---------##
	public function actionQuestions($id, $quarter)
	{
		$intSurTemplate = $id;
		$surveyDetails = $this->findModel($intSurTemplate);
		$searchModel = new SurveyTemplateQuestionsSearch();
		$objQuarterDetails = SurveyQuarters::find()
							->where(['survey_template_id' => $intSurTemplate, 'quarter' =>$quarter ])
							->asArray()
							->one();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams ,$intSurTemplate);
		return $this->render('question-list', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'surveyDetails' => $surveyDetails,
			'survey_template_id' => $intSurTemplate,
			'objQuarterDetails' => $objQuarterDetails,
			'quarter' => $quarter
		]);
	}

	##----- Add survey Qusestions ----##
	public function actionAddquestion($id , $qid)
	{
		$intSurveyId = $id;
		$model = new SurveyTemplateQuestions();
		$propertyList = $model->getPropertyList();
		$session = Yii::$app->session;
		if($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			$model->survey_template_id = $intSurveyId;
			$model->created_at = $model->updated_at = new Expression('NOW()');
			if ($model->save()) {
				## Need to fetch all the location nodes of selected property & assign to question.
				$locationNodes = LocationNodes::find()
							    ->where([
							    	'property_type_id' => $model->property_type_id,
							    	'status' => 'Active'
							    	])
								->asArray()
								->all();
				//echo '<pre/>';print_r($locationNodes);
				if(!empty($locationNodes)) {
					$primaryArr = [];
					foreach($locationNodes as $data) {
						$data['survey_template_question_id'] = $model->id;

						$res =$this->addquestionnodes($data,$primaryArr);
						$primaryArr[$data['id']] = $res;
					}
				}//echo '<pre/>';print_r($primaryArr);exit;
				$session->setFlash('success', QUESTION_ADD_SUCC);
			} else {
				$session->setFlash('error', QUESTION_ADD_ERR);
			}
			return $this->redirect(['questions', 'id' => $intSurveyId, 'quarter' =>$qid]);
			//return $this->redirect(Yii::$app->request->referrer);
		}
		return $this->render('question-form',[
					'model' => $model,
					'propertyList' => $propertyList,
					'intSurveyId' => $intSurveyId
		]);
	}

	## Insert all the question nodes
	public function addquestionnodes($data, $primaryArr)
	{
			$nodeModel = new SurveyTemplateQuestionNodes();
			## Need to insert all the nodes to question nodes
			$nodeModel->name = $data['name'];
			$nodeModel->included = ($data['location_node_id'] == '' || $data['location_node_id'] == NULL) ? '1' : '0';
			$nodeModel->capture_level = ($data['location_node_id'] == '' || $data['location_node_id'] == NULL) ? '1' : '0';
			$nodeModel->location_node_id = $data['id'];
			$nodeModel->survey_template_question_node_id = (isset($data['location_node_id']) && $data['location_node_id']!='') ? $primaryArr[$data['location_node_id']] : '';
			$nodeModel->survey_template_question_id = $data['survey_template_question_id'];
			$intInsertedID = $nodeModel->save();
			return $nodeModel->id;
	}

	##----- Update survey Qusestions ----##
	public function actionUpdatequestion($id ,$qid)
	{
		$model = SurveyTemplateQuestions::find()
				->where(['id' => $id])
				->one();

		$oldProperty = $model->property_type_id;
		$propertyList = $model->getPropertyList();
		$session = Yii::$app->session;
		if($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			if ($saver=$model->save()) {
				## If property type is updated ,respective nodes should be assigned & current should be deleted.
				if($oldProperty != $model->property_type_id) {

				   ## first delete previously assigned nodes
				   SurveyTemplateQuestionNodes::deleteAll('survey_template_question_id = :id', [':id' => $model->id]);
					# Need to fetch all the location nodes of selected property & assign to question.
				$locationNodes = LocationNodes::find()
							    ->where([
							    	'property_type_id' => $model->property_type_id,
							    	'status' => 'Active'
							    	])
								->asArray()
								->all();
				//echo '<pre/>';print_r($locationNodes);
				if(!empty($locationNodes)) {
					$primaryArr = [];
					foreach($locationNodes as $data) {
						$data['survey_template_question_id'] = $model->id;

						$res =$this->addquestionnodes($data,$primaryArr);
						$primaryArr[$data['id']] = $res;
					}
				}

				}
				$session->setFlash('success', QUESTION_UPDATE_SUCC);
			} else {
				$session->setFlash('error', QUESTION_UPDATE_ERR);
			}
			$this->redirect(['questions', 'id' => $model->survey_template_id, 'quarter' =>$qid]);
		}
		return $this->render('question-form',[
					'model' => $model,
					'propertyList' => $propertyList,
					'intSurveyId' => $model->survey_template_id
		]);
	}

	##----- View question detail-------##
	public function actionQuestiondetails($id,$qid)
	{
		$model = SurveyTemplateQuestions::find()
				->where(['id' => $id])
				->one();
		$session = Yii::$app->session;
		## Code to add new column
		$dataTemplatemodel = new DataFieldTemplates();
		if($dataTemplatemodel->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($dataTemplatemodel);
			}
			$dataTemplatemodel->survey_template_question_id = $id;
			$dataTemplatemodel->order = $dataTemplatemodel->getOrder($model->id); //1 coz we are adding 1st column
			//echo '<pre/>';print_r(Yii::$app->request->post());exit;
			if($dataTemplatemodel->field_type != 'selection') {
				$dataTemplatemodel->options = '';
			}
		   if($dataTemplatemodel->save()) {
		   		$session->setFlash('success', COL_ADD_SUCC);
		   } else {
		   	    $session->setFlash('error', COL_ADD_ERR);
		   }
		   //$this->redirect(['admin/question/details', 'id' => $model->id]);
		    return $this->redirect(Yii::$app->request->referrer);
	    }

	    ## Fetch all the question nodes
	    $arrNodes = SurveyTemplateQuestionNodes::find()
	    			->where(['survey_template_question_id' => $model->id])
	    			->orderBy('id')
	    			->asArray()
	    			->all();

	    ## Fetch all data columns
	    $arrColumns = DataFieldTemplates::find()
	    			->where(['survey_template_question_id' => $model->id])
	    			->with('exclusions')
	    			->orderBy('order')
	    			->asArray()
	    			->all();	//echo '<pre/>';print_r($arrColumns);

		$listData = $dataTemplatemodel->fieldTypes;

		## Get quarter details
		$objQuarterDetails = SurveyQuarters::find()
							->where(['survey_template_id' => $model->survey_template_id, 'quarter' =>$qid ])
							->asArray()
							->one();
		return $this->render('view',[
					'model' => $model,
					'dataTemplatemodel' => $dataTemplatemodel,
					'arrFieldTypes' => $listData,
					'arrNodes' => $arrNodes,
					'arrColumns' => $arrColumns,
					'objQuarterDetails' => $objQuarterDetails,
					]);
	}

	##------listing of  node categories for survey-----##
	public function actionNodecategories($id)
	{
		$intSurTemplate = $id;
		$surveyDetails = $this->findModel($intSurTemplate);
		$searchModel = new SurveyTemplateNodeCategoriesSearch();

		$dataProvider = $searchModel->search(Yii::$app->request->queryParams ,$intSurTemplate);
		return $this->render('node-categories-index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'surveyDetails' => $surveyDetails,
			'survey_template_id' => $intSurTemplate
		]);

	}

	##--------- add node category for survey ----##
	public function actionAddnode($id)
	{
		$intSurveyId = $id;
		$model = new SurveyTemplateNodeCategories();
		$propertyList = SurveyTemplateQuestions::getPropertyList();
		$session = Yii::$app->session;
		if($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			$model->survey_template_id = $intSurveyId;
			$model->active = 'Active';
			if ($model->save()) {
				$session->setFlash('success', NODECAT_ADD_SUCC);
			} else {
				$session->setFlash('error', NODECAT_ADD_ERR);
			}
			$this->redirect(['nodecategories', 'id' => $intSurveyId]);
		}
		return $this->render('node-category-form',[
					'model' => $model,
					'propertyList' => $propertyList,
					'intSurveyId' => $intSurveyId
		]);
	}

	##--------- update node category for survey ----##
	public function actionUpdatenode($id)
	{
		$model = SurveyTemplateNodeCategories::find()
				->where(['id' => $id])
				->one();
		$propertyList = SurveyTemplateQuestions::getPropertyList();
		$session = Yii::$app->session;
		if($model->load(Yii::$app->request->post())) {

			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			if ($model->save()) {//echo '<pre/>';print_r($model);exit;
				$session->setFlash('success', NODECAT_UPDATE_SUCC);
			} else {
				$session->setFlash('error', NODECAT_UPDATE_ERR);
			}
			$this->redirect(['nodecategories', 'id' => $model->survey_template_id]);
		}
		return $this->render('node-category-form',[
					'model' => $model,
					'propertyList' => $propertyList,
					'intSurveyId' => $model->survey_template_id
		]);
	}

	##-------- delete node category-----##
	public function actionDeletenode()
	{
		$intId = Yii::$app->request->post('id');
		$session = Yii::$app->session;
		$model = SurveyTemplateNodeCategories::find()
				->where(['id' => $intId])
				->one();
		$intSurveyId = $model->survey_template_id;
		if($model->delete()) {
			$session->setFlash('success', NODECAT_DEL_SUCC);
		} else {
			$session->setFlash('error', NODECAT_DEL_ERR);
			//print_r($model->getErrors());exit;
		}
        return $this->redirect(['nodecategories', 'id' => $intSurveyId]);
	}

	##---------audit trail--------##
	public function actionAudit_trail($id)
	{
		## Check if current url is for survey audit or question audit
		if (strpos(Yii::$app->request->url,'question') !== false) {
    		$strAuditableType = 'SurveyTemplateQuestion';
    		$strTitle = 'Questions';
		} else {
			$strAuditableType = 'SurveyTemplate';
			$strTitle = 'Surveys';
		}
		$int_auditable_id = $id;
		$searchModel = new AuditSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $int_auditable_id, $strAuditableType);
		return $this->render('audit-trail',[
				'searchModel' => $searchModel,
            	'dataProvider' => $dataProvider,
            	'strTitle' => $strTitle
			]);
	}


	##--------view changes in audit trail-------##
	/* the logic used here is after considering existing db records.*/
	public function actionViewchanges()
	{
		$intAuditId = Yii::$app->request->post('audit_id');
		$model = Audits::find()
				->where(['id' => $intAuditId])
				->asArray()
				->One();
	    if(!empty($model)) {
	    	$strchanges = ($model['changes']);
	    	$strchanges = preg_replace("/---/", '', $strchanges , 1);
	    	$strchanges = trim($strchanges);
			//echo $strchanges;
	    	$changesArr = [];
	    	$s=explode("\n",$strchanges);

	    	if($model['auditable_type'] == 'SurveyTemplate') {
		    	if($model['action'] == 'create') {
			    	foreach (explode("\n",$strchanges) as $pair)
		  			{
		  				//if(!empty($pair)) {
		                list ($k,$v) = explode (':',$pair, 2); //echo $v.$i;
		                if($k == 'survey_template_category_id') {
		                	$strCategory = SurveyTemplateCategories::find()
		                					->select('name')
		                					->where(['id' => $v])
		                					->asArray()
		                					->one();
		                	if(!empty($strCategory)) {
		                		$v = $strCategory['name'];
		                	}

		                }
		                $changesArr[$k] = $v;
		                //}
		  			}
	  			} else {
	  			$arrDelimeters = ["contributor_type :","publication :","survey_template_category_id :","name :","contributor_type:","publication:","survey_template_category_id:","name:"];
	  			$stringArr=[];//echo $strchanges;
	  			foreach($arrDelimeters as $del) {

	  				if (strpos($strchanges,$del) !== false) {

	  					$strchanges = explode($del, $strchanges);//echo 'before :'.print_r($arr);
    					//echo 'k +++'.$del;echo 'v +++'.$strchanges[0];
    					$stringArr[] = $del.$strchanges[1];//echo'bafter :'.print_r($arr);
    					$strchanges = $strchanges[0];
    				}
	  			}
	  				foreach ($stringArr as $pair)
		  			{
		  				list ($k,$v) = explode (':',$pair, 2);
		  				preg_replace("/-/", '', $v , 1);
		  				$v =explode("\n" ,trim($v));
		  				$v =preg_replace("/-/", '', $v , 1);//print_r($v);
		  				if(trim($k) == 'survey_template_category_id') {
		  					$arrCategory =[];
		                	$arrCategory = SurveyTemplateCategories::find()
		                					->select('name')
		                					->where(['id' => $v])
		                					->asArray()
		                					->all(); //print_r($arrCategory);
		                					//echo $strCategory->createCommand()->getRawSql();
						 $versionArr =$v= [];
		                	if(!empty($arrCategory)) {
		                		foreach($arrCategory as $arr) {
		                			$v[] = $arr['name'];
		                		}//print_r($v);
		                		//$v = $strCategory['name'];
		                	}

		                }
		  				$changesArr[$k] = $v;
		  			}
				}
  			}

  		if($model['auditable_type'] == 'SurveyTemplateQuestion') {
  			//echo $strchanges;exit;
  			## these are the possible delimeters we need to break the string with.
	    	$arrDelimiters = array("use_categories:","property_type_id:","information:","old_type:","survey_template_id:","question:");
	    	$uniformText = str_replace($arrDelimiters, "-||-", $strchanges);//echo $strchanges;exit;
	    	$output =explode("-||-", $uniformText);
	    	$delimitersArr = [];
			## Need to check which key is present from delimiters :
			foreach($arrDelimiters as $del) {
				if (strpos($strchanges, $del) !== FALSE) { // Yoshi version
					$delimitersArr[] = $del;
				}
			}

	    	if($model['action'] == 'create') {
	    		//print_r($output);print_r($arrDelimiters);
	    		if(!empty($output)) {
	    			## Exploding returns 1st index blank in our case, so unset it.
	    			unset($output[0]);
	    			## Rearrange indexes
	    			$output = array_values($output);
	    			## Combine array with 1 as key & other as value
	    			$output = array_combine($delimitersArr ,$output);
	       		}
	    		//print_r($delimitersArr);exit;
		    	foreach ($output as $k=>$v)
	  			{
	  				## Remove some special characters from string
	  				$k = preg_replace('/:/', '', $k, 1);
	  				$v = preg_replace("/[|-]/", '', $v, 1);
	  				$v = preg_replace("/-/", '', $v, 1);
	  				if($k == 'property_type_id') {
	                	$strProperty = PropertyTypes::find()
	                					->select('name')
	                					->where(['id' => $v])
	                					->asArray()
	                					->one();
	                	if(!empty($strProperty)) {
	                		$v = $strProperty['name'];
	                	}

	                }
	                if($k == 'survey_template_id') {
	                	$strProperty = SurveyTemplates::find()
	                					->select('name')
	                					->where(['id' => $v])
	                					->asArray()
	                					->one();
	                	if(!empty($strProperty)) {
	                		$v = $strProperty['name'];
	                	}

	                }
	                //$v = preg_replace("/[|-]/", '', $v, 1);
	                $changesArr[$k] = $v;
	            }
	  			}//print_r($changesArr);
	  			else {
	  				//print_r($output);exit;
	    			if(!empty($output)) {
	    				## Exploding returns 1st index balnk in our case, so unset it.
	    				unset($output[0]);
	    				## Rearrange indexes
	    				$output = array_values($output);
	    				$output = array_combine($delimitersArr ,$output);
	       			}//print_r($output);exit;
	  				foreach ($output as $k=>$v)
		  			{
		  				$k = preg_replace("/:/", '', $k , 1);
		  				## For quest, information in Rails sys they've used separate format to store.
		  				## So need to explode it as following:
		  				if(in_array($k , ['information','question'])) {
		  					## Exploding will return v1 as blank, so I've considered 3 params ,v1 & v2.
		  					if (strpos($v, "|-") !== false) {
		  							$v = explode ("|-",$v, 3);
		  							unset($v[0]);
		  							## Rearrange indexes
		  							$v = array_values($v); //print_r($v);exit;
		  						} else {
		  							$v = explode ("\n",trim($v), 2);
		  						}
		  					$v =preg_replace("/-/", '', $v , 1);

		  				} else {//echo $v;exit;
		  					$v = explode ("\n",trim($v), 2);//print_r($v);exit;
		  					$v =preg_replace("/-/", '', $v , 1);


		  				}//print_r($v);exit;
		  				if($k == 'property_type_id') {
		  					$arrProperty =[];
	                		$arrProperty = PropertyTypes::find()
	                					->select('name')
	                					->where(['id' => $v])
	                					->asArray()
	                					->all();
	                		if(!empty($arrProperty)) {
	                			$v = [];
	                			foreach($arrProperty as $arr) {
		                			$v[] = $arr['name'];
		                		}
	                		}

	                	}
	                	if($k == 'survey_template_id') {
	                		$arrTemplate = [];
	                		$arrTemplate = SurveyTemplates::find()
	                					->select('name')
	                					->where(['id' => $v])
	                					->asArray()
	                					->all();
	                		if(!empty($arrTemplate)) {
	                			$v = [];
	                			foreach($arrTemplate as $arr) {
		                			$v[] = $arr['name'];
		                		}
	                		}

	                	}$changesArr[$k] = $v;//print_r($v);exit;

		  			}//print_r($changesArr);exit;
				}
  			}
  		}

            $changes = '';
            if(!empty($changesArr)) {
            	if($model['action'] == 'create') {
	            	foreach($changesArr as $k=>$v) {
						$changes .= "<tr><td>".$k."</td><td>---</td><td>".($v)."</td></tr>";
	            	}
            	} else {
            		foreach($changesArr as $k=>$v) {
            			$v0 = rtrim($v[0]," - ");
            			if(isset($v[1])) {$v1 =$v[1]; } else {$v1 = "";}
						$changes .= "<tr><td>".$k."</td><td>".$v0."</td><td>".$v1."</td></tr>";
	            	}
            	}

            }
            return json_encode(['changes' => $changes , 'modified_on' => $model['created_at']]);
	    	//print_r($changesArr);
	    }
	//}

   ##-------function to change capture level------##
    public function actionChangecapturelevel()
    {
    	$intId = Yii::$app->request->post('id');
    	$boolCapturelevel = Yii::$app->request->post('capture_level');
    	if($boolCapturelevel == '1') {
    		$newboolCapture = '0';
    	} else {
    		$newboolCapture = '1';
    	}

    	$connection = Yii::$app->db;
		$boolAns = $connection ->createCommand()
 				->update('survey_template_question_nodes', ['capture_level' => $newboolCapture], 'id ='.$intId)
 		    	->execute();
 		 ## If parent node is captured, child nodes wont be captured & vice-versa.
 		 if($boolAns) {
 		 	$boolStat = $connection ->createCommand()
 				->update('survey_template_question_nodes', ['capture_level' => $boolCapturelevel], 'survey_template_question_node_id ='.$intId)
 		    	->execute();
 		    	if($boolStat) {
 		    		return json_encode(['result' => 'success', 'message' => CAPTURE_SUCC]);
 		    	} else {
 		    		return json_encode(['result' => 'error', 'message' => CAPTURE_ERR]);
 		    	}
 		 }   else {
 		 	return json_encode(['result' => 'error', 'message' => CAPTURE_ERR]);
 		 }
	  }

	  ## Delete data column of output table ##
	  public function actionDeletecolumn()
	  {
	  	$intId = Yii::$app->request->post('id');
	  	$connection = Yii::$app->db;
	  	$session = Yii::$app->session;
	  	$model = DataFieldTemplates::findOne($intId);
	  	$intOrder = $model->order;
	  	$intQuesId = $model->survey_template_question_id;
		$boolStatus = $connection ->createCommand()
			->delete('data_field_templates', 'id = '.$intId)
			->execute();
		if($boolStatus) {
			$connection ->createCommand("UPDATE data_field_templates SET `order` = `order`-1 WHERE `order` >".$intOrder." AND survey_template_question_id=".$intQuesId)
			->execute();
			$session->setFlash('success', COL_DEL_SUCC);
		} else {
			$session->setFlash('error', COL_DEL_ERR);
		}
        return $this->redirect(Yii::$app->request->referrer);
	  }

	  ## Edit Data column
	  public function actionUpdatecolumn($id)
	  {
	  	$dataTemplatemodel = DataFieldTemplates::findOne($id);
	  	$listData = $dataTemplatemodel->fieldTypes;
	  	$session = Yii::$app->session;
	  	if ($dataTemplatemodel->load(Yii::$app->request->post())) {
	  		if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($dataTemplatemodel);
			}
			if($dataTemplatemodel->field_type != 'selection') {
				$dataTemplatemodel->options = '';
			}
	  		if($dataTemplatemodel->save()) {
	  			$session->setFlash('success', COL_UPDATE_SUCC);
        	} else {
        		$session->setFlash('error', COL_UPDATE_ERR);
        	}
        	//return $this->redirect(['admin/question/details', 'id' => (string) $dataTemplatemodel->survey_template_question_id]);
        	return $this->redirect(Yii::$app->request->referrer);
    	}
	  	return $this->renderAjax('_colum_form', [
                        'dataTemplatemodel' => $dataTemplatemodel,
                        'arrFieldTypes' => $listData
            ]);
	  }

	  /**
	* UPdate Positon of nodes
	*/
	public function actionUpdateorder()
	{
		$arrPositions = Yii::$app->request->post('sectionsid');
		$intCount = 1;
		//print_r($arrPositions);
		$arrOutput = DataFieldTemplates::setChangeSequence($arrPositions, $intCount);
		return $arrOutput;
	}

	## Exclude /include columns ##
	public function actionCheckexclusion()
	{
		$intTemplateID = Yii::$app->request->post('column');
		$intquesNodeID = Yii::$app->request->post('node');
		$boolExcluded = Yii::$app->request->post('excluded');

		## If record is xcluded i.e entry is present ins node_exclusion ,delete
		## record else add new entry.
		if($boolExcluded) {
		$model = NodeFieldExclusions::find()->where([
			'data_field_template_id' => $intTemplateID ,
			'survey_template_question_node_id' => $intquesNodeID
			])->one();//print_r($model);exit;
		if($model->delete()) {
			return json_encode(['result' => 'success', 'message' => COL_INC_SUCC]);
		} else {
			return json_encode(['result' => 'error', 'message' => COL_INC_ERR]);
		}

		} else {
		$model = new NodeFieldExclusions();
		$model->data_field_template_id = $intTemplateID;
		$model->survey_template_question_node_id = $intquesNodeID;
		if($model->save()) {
			return json_encode(['result' => 'success', 'message' => COL_EXC_SUCC]);
		} else {
			return json_encode(['result' => 'error', 'message' => COL_EXC_ERR]);
		}
		}
	}


	##-------function to change included/excluded of a node------##
    public function actionChangeincluded()
    {
    	$intId = Yii::$app->request->post('id');
    	$boolIncluded = Yii::$app->request->post('is_included');
    	$include_succ = $include_err = '';
    	if($boolIncluded == '1') {
    		$newboolIncluded = '0';
    		$include_succ = 'Node excluded successfully';
    	} else {
    		$newboolIncluded = '1';
    		$include_succ = 'Node included successfully';
    	}

    	$connection = Yii::$app->db;
		$boolAns = $connection ->createCommand()
 				->update('survey_template_question_nodes', ['included' => $newboolIncluded], 'id ='.$intId)
 		    	->execute();
 		 if($boolAns) {
 		    	return json_encode(['result' => 'success', 'message' => $include_succ]);
 		 } else {
 		    	return json_encode(['result' => 'error', 'message' => INCLUDE_ERR]);
 		 }
	  }

	  ## function to distribute survey
	  public function actionDistributesurvey()
	  {
			$arrpostedData = Yii::$app->request->post();
			$arrSurveys = $arrpostedData['selection'];
			$strQuarter = $arrpostedData['curr_quarter'];
			if(!empty($arrSurveys)) {
				$arrSurveyIds = [];
				foreach($arrSurveys as $arr) {
				$arrLocationNodes = json_decode(SurveyTemplateQuestions::getQuestionNodes($arr));
				if(!empty($arrLocationNodes->nodesArr) && !empty($arrLocationNodes->propertyArr) && !empty($arrLocationNodes->contributorArr)) {
						$objQuery = (new \yii\db\Query())
    							->select(['contributors.id'])
    							->from('contributors')
    							->distinct()
						        ->innerJoin('contributor_nodes', 'contributor_nodes.contributor_id = contributors.id' )
		      					->where(['IN', 'location_node_id', $arrLocationNodes->nodesArr])
		           				->andWhere(['IN', 'property_type_id', $arrLocationNodes->propertyArr]);
		           	    if($arrLocationNodes->contributorArr[0] != "Both") {
		           				$objQuery->andWhere(['contributor_type' => $arrLocationNodes->contributorArr[0]]);
		           		 }

		           		$objCommand = $objQuery->createCommand();
		           		//var_dump($objQuery->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
						$arrData = $objCommand->queryAll();
						//echo '<pre/>';print_r($arrData);
						if(!empty($arrData)) {
							for($i=0; $i<count($arrData); $i++) {
								$insertRow[] = [
				    				'contributor_id' => $arrData[$i]['id'],
									'survey_id' => $arr,
									'quarter' => $strQuarter
			    				];
							}
							$connection = Yii::$app->db;
							$boolStatus=$connection ->createCommand()
				 				->batchInsert('temp_distribution',['contributor_id', 'survey_id','quarter'], $insertRow)
								->execute();
							if($boolStatus) {
								## update status in db
								$arrSurveyIds[] = $arr;
							}
						}## end of !empty($arrData)
					}## end of if
				}## end of for loop
				$session = Yii::$app->session;
				if(!empty($arrSurveyIds)) {
					$boolUpdated = SurveyQuarters::updateAll(['distributed' => 1, 'updated_at' => new Expression('NOW()')],['and' ,['IN', 'survey_template_id', $arrSurveyIds], ['=','quarter',$strQuarter]]);
					$session->setFlash('success', DISTRIBUTE_SUCC);
				} else {
					$session->setFlash('error', DISTRIBUTE_ERR);
				}
			}## !empty($model)
			$this->redirect(Yii::$app->request->referrer);
	  }


	##--------List out all the output tables -----##
	public function actionOutputtables($id , $qid)
	{
		$surveyDetails = $this->findModel($id);
		$arrListOutput = $surveyDetails->searchOutputtables($id ,$qid );
		$arrComments = $surveyDetails->getComments($id ,$qid);
		$objQuarterDetails = SurveyQuarters::find()
							->where(['survey_template_id' => $id, 'quarter' =>$qid ])
							->asArray()
							->one();
		return $this->render('outputtable-list',[
			'surveyDetails' => $surveyDetails,
			'arrListOutput' => $arrListOutput,
			'quarter' => $qid,
			'arrComments' => $arrComments,
			'objQuarterDetails' => $objQuarterDetails
			]);
	}

	##------ Calculations function -----##
	public function actionCalculations($id, $quarter, $qid)
	{
		$arrColumnHeadings = $getContributorsCode = $arrLocationNodeIDS = $arrNodeIDS = $arrDataIDS =$parentNodeArr =[];
		$arrParentMean = $arroutputColumnExclusions = $getGrossArr = $getOldQuarter = $oldQuarterMean = $oldQuarterN = [];
		$higestArr = $arrABRvalues = [];
		$model = OutputTables::findOne($id); //print_r($model); die;
		$surveyDetails = SurveyTemplates::find()
						->select(['survey_templates.*','question as survey_question'])
						->joinWith(['surveyQuestions'])
						->where(['survey_template_questions.id' => $model->survey_template_question_id])
						//->asArray()
						->one();
		## Get all parent & child nodes
		$arrallNodes= json_decode($this->getAllNodes($model->survey_template_question_id , $model->parent_node_visibility , $model->show_parent_average));
		$arrallNodes= ArrayHelper::toArray($arrallNodes);
		$arrNodes = $arrallNodes['arrNodes'];
		$parentNodeArr = $arrallNodes['parentArr'];



        $arrNodeIDS = ArrayHelper::getColumn($arrNodes ,'id');
        //echo '<pre/>';print_r($arrallNodes);exit;

        ## Fetch column headings
       //$arrColumnHeadings =ArrayHelper::toArray($model->outputColumnHeadings);//need to fire query cause we need order from other table
        $arrColumnHeadings =  OutputTableColumnHeadings::find()
        					  ->joinWith('dataFields')
        					  ->where(['output_table_id' => $model->id])
        					  ->orderBy('order')
        					  ->asArray()
        					  ->all(); //print_r($arrColumnHeadings->createCommand()->getRawSql()); die;

        $arroutputColumnHeadings = ArrayHelper::map($arrColumnHeadings, 'data_field_template_id', 'heading');
        $arrDataIDS = ArrayHelper::getColumn($arrColumnHeadings ,'data_field_template_id');
        //echo '<pre/>';print_r($arrColumnHeadings);exit;

         ## Fetch column values with return values
        $arrColumnValues =ArrayHelper::toArray($model->fieldFilters);
        $arrValues = ArrayHelper::map($arrColumnValues , 'data_field_template_id','value');
        //echo '<pre/>';print_r($arrValues);exit;
        ## Fetch column exclusions
        $arrColumnExclusions =ArrayHelper::toArray($model->outputColumnExclusions);
        ## For older records ,it gives value for exclusion as 0 also,ie.e not excluded
        if(!empty($arrColumnExclusions)) {
			foreach($arrColumnExclusions as $subKey => $subArray){
				if($subArray['excluded'] == 0){
					unset($arrColumnExclusions[$subKey]);
				}
			}
			$arroutputColumnExclusions = ArrayHelper::map($arrColumnExclusions, 'data_field_template_id', 'excluded');

		}
        //echo '<pre/>';print_r($arroutputColumnExclusions);exit;
        ## Find Mean  & Deviation for selected quarter
		$getMeanArr = $this->getQuarterMean($quarter, $qid , $arrDataIDS , $arrNodeIDS);
		$getMeanArr = json_decode($getMeanArr );
		$getMeanArr = ArrayHelper::toArray($getMeanArr);


		## Get Contributor's Code if required by output table
		if($model->contributor_code == '1') {
			$arrLocationNodeIDS = ArrayHelper::getColumn($arrNodes ,'location_node_id');
        	$getContributorsCode = $this->getContributorsCode($arrLocationNodeIDS ,$qid ,$arrNodes, $model->survey_template_question_id);
        	//if($_SERVER["REMOTE_ADDR"]=="195.168.77.18") { echo '<pre/>';print_r($getContributorsCode);exit; }
		}//echo '<pre/>';print_r($arrColumnValues);exit;

		## Calculate Gross Income Yields
		if(in_array('Gross Income Yields', $arrValues)) {
			$getGrossArr = $this->getGrossIncomeYields($arrColumnValues ,$getMeanArr['arrMean'] ,$arrNodes);
		}//echo '<pre/>';print_r($getGrossArr);exit;

		## Calculate old quarter values
		if(in_array('Current Q - Last Q', $arrValues)) {
			$getOldQuarter = json_decode($this->getPreviousQuarter($quarter , $surveyDetails->id ,$arrDataIDS , $arrNodeIDS));
			$getOldQuarter = ArrayHelper::toArray($getOldQuarter);
			$oldQuarterMean = $getOldQuarter['arrOldMean'];
			$oldQuarterN = $getOldQuarter['arrOldN'];
		}

		## Calculate highest value
		if(in_array('Highest Value', $arrValues)) {
			$higestArr = isset($getMeanArr['arrHighest']) ? $getMeanArr['arrHighest'] : [];
		}

		## calculate parent average
		if($model->show_parent_average == '1' || $model->parent_node_visibility == 'text') {
			$arrParentMean = $this->calculateParentMean($parentNodeArr ,$getMeanArr['arrMean'] ,$arrDataIDS,$getMeanArr['arrN'], $arrColumnValues ,$oldQuarterMean ,$oldQuarterN, $qid);
		}

		## calculate a,b,r values
		if($model->a_b_r == 1) {
			$arrABRvalues = $this->getabrvalues($arrNodeIDS ,$getMeanArr['arrMean'] ,$arroutputColumnHeadings);
		}
		// $arrParentMean['16624']['4414']['mean']++;
		// print_r([
		//         'MeanArr' => isset($getMeanArr['arrMean']) ?  $getMeanArr['arrMean'] : [],
		//         'arrParentMean' => $arrParentMean,
		//         'grossArr' => $getGrossArr,
		//         'aaaa' => $arrLocationNodeIDS
		//       ]);
		//     die();

//if($_SERVER["REMOTE_ADDR"]=="195.168.77.18") { echo '<pre/>';print_r($getMeanArr['arrSD']);exit; }
 //echo '<pre/>';print_r($getMeanArr);exit; 
		return $this->render('calculations', [
            'model' => $model,
            'arrColumnHeadings' => $arrColumnHeadings,
            'arrColumnValues' => $arrColumnValues,
            'arrNodes' => $arrNodes,
            'arroutputColumnExclusions' => $arroutputColumnExclusions,
            'MeanArr' => isset($getMeanArr['arrMean']) ?  $getMeanArr['arrMean'] : [],
            'SDArr' => isset($getMeanArr['arrSD']) ?  $getMeanArr['arrSD'] : [],
            'Narr' => isset($getMeanArr['arrN']) ?  $getMeanArr['arrN'] : [],
            'contributorsCode' => $getContributorsCode,
            'arrParentMean' => $arrParentMean,
            'grossArr' => $getGrossArr,
            'surveyDetails' => $surveyDetails,
            'oldQuarter' => $oldQuarterMean,
            'higestArr' => $higestArr,
            'quarter_id' => $qid,
            'quarter' => $quarter,
            'arrABRvalues' => $arrABRvalues
        ]);
	}

	## GEt all the surveys to find the mean
    public function getQuarterMean($quarter, $selectedQuarterID ,$arrDataIDS , $arrNodeIDS)
    {
        $arrDatafields = $arrSD = $arrMean = $arrN = $arrHighest =[];
        /*$arrDatafields = Surveys::find()->select(['avg(value) as average_val','survey_template_question_node_id','data_field_template_id','sum(value)','count(surveys.id) as tot_cnt','STDDEV(value) as sd'])
                 ->joinwith('datafields')
                 ->where([ 'survey_quarter_id' => $selectedQuarterID , 'quarter' => $quarter])
                 ->andWhere(['<>','value',''])
                 ->andWhere(['IN', 'survey_template_question_node_id', $arrNodeIDS])
                 ->groupBy(['survey_template_question_node_id','data_field_template_id'])
                 ->all();*/ //taking more execution time
         //echo '<pre/>';print_r($arrDatafields);exit;
        $arrDatafields = (new \yii\db\Query())
        		 ->select(['avg(value) as average_val','survey_template_question_node_id','data_field_template_id','sum(value)','count(surveys.id) as tot_cnt','STDDEV(value) as sd','max(cast(value as unsigned)) as highest_val'])
                 ->from('surveys')
                 //->joinwith('datafields')
                 ->leftJoin('data_fields', 'data_fields.survey_id = surveys.id' )
                 ->where([ 'survey_quarter_id' => $selectedQuarterID , 'quarter' => $quarter])
                 ->andWhere(['<>','value',''])
                 ->andWhere(['included'=> '1'])
                 ->andWhere(['IN', 'survey_template_question_node_id', $arrNodeIDS])
                 ->groupBy(['survey_template_question_node_id','data_field_template_id'])
                 ->orderBy('')
                 ->all();
        //echo '<pre/>';print_r($arrDatafields);exit;
        if(!empty($arrDatafields)) {
        	  ## Mean Array
               $arrMean = ArrayHelper::map($arrDatafields , 'data_field_template_id','average_val', 'survey_template_question_node_id');

               ## SD array
               $arrSD = ArrayHelper::map($arrDatafields , 'data_field_template_id','sd', 'survey_template_question_node_id');

               ## N array
               $arrN = ArrayHelper::map($arrDatafields , 'data_field_template_id','tot_cnt', 'survey_template_question_node_id');

               ## Highest value array
               $arrHighest = ArrayHelper::map($arrDatafields , 'data_field_template_id','highest_val', 'survey_template_question_node_id');
            }


        return json_encode([ 'arrMean' => ($arrMean) , 'arrSD' => $arrSD , 'arrN' => $arrN, 'arrHighest' => $arrHighest]);
    }

    ##------ Find The Standard Deeviation -------##
    public function getContributorsCode($locationnodesArr ,$qid ,$arrNodes, $questID)
    {
      $data = [];
      /*
      SELECT `companies`.`contributor_code`,`survey_template_question_nodes`.`location_node_id`
      FROM `data_fields`
      JOIN `surveys` ON `surveys`.`id` = `data_fields`.`survey_id`
      JOIN `survey_template_question_nodes` on `survey_template_question_nodes`.`id` = `data_fields`.`survey_template_question_node_id`
      JOIN `contributors` on `contributors`.`id` = `surveys`.`contributor_id`
      JOIN `companies` on `companies`.`id` = `contributors`.`company_id`
      where `survey_template_question_nodes`.`location_node_id` IN ('1417', '1418', '1419', '1420', '1421', '1422', '1423', '1424', '1425', '1426', '1427', '1428', '1429', '1430', '1431', '1432', '1433', '1434', '1435', '1417')
      and `survey_quarter_id`='6892'
      */

    	$query = (new \yii\db\Query())
                ->select('`companies`.`contributor_code`,`survey_template_question_nodes`.`location_node_id`')
                ->from('data_fields');
      $query->innerJoin('surveys', '`surveys`.`id` = `data_fields`.`survey_id`');
      $query->innerJoin('survey_template_question_nodes', '`survey_template_question_nodes`.`id` = `data_fields`.`survey_template_question_node_id`');
      $query->innerJoin('contributors', '`contributors`.`id` = `surveys`.`contributor_id`' );
      $query->innerJoin('companies', '`companies`.`id` = `contributors`.`company_id`' );


      $query->where(['survey_quarter_id' => $qid]);
      $query->andWhere(['IN', '`survey_template_question_nodes`.`location_node_id`', $locationnodesArr]);
      $query->andWhere(['surveys.completed'=>'1']);
      $query->andWhere(['survey_template_question_id'=>$questID]);
      // $query->andWhere(['data_fields.included'=>'1']);
/*if ($_SERVER['REMOTE_ADDR']=="195.168.77.18") {

    	$query1 = (new \yii\db\Query())
                ->select('`companies`.`contributor_code`,`survey_template_question_nodes`.`location_node_id`')
                ->from('data_fields');
      $query1->innerJoin('surveys', '`surveys`.`id` = `data_fields`.`survey_id`');
      $query1->innerJoin('survey_template_question_nodes', '`survey_template_question_nodes`.`id` = `data_fields`.`survey_template_question_node_id`');
      $query1->innerJoin('contributors', '`contributors`.`id` = `surveys`.`contributor_id`' );
      $query1->innerJoin('companies', '`companies`.`id` = `contributors`.`company_id`' );


      $query1->where(['survey_quarter_id' => $qid]);
      $query1->andWhere(['IN', '`survey_template_question_nodes`.`location_node_id`', $locationnodesArr]);
      $query1->andWhere(['surveys.completed'=>'1']);
      $query1->andWhere(['survey_template_question_id'=>$questID]);

	  print_r($query1->createCommand()->getRawSql()); die;
      //print_r($data =$query->all()); die;
}*/
      // print_r(func_get_args());
      // die();
      $data =$query->all();
      $dataArray = [];
      if(!empty($data)) {
        foreach($arrNodes as $node) {
          foreach($data as $val) {
            if($node['location_node_id'] == $val['location_node_id']) {
              $dataArray[$node['id']][] = $val['contributor_code'];
            }
          }
        }
      }
      return $dataArray;

    // 	$query = (new \yii\db\Query())
    //             ->select('*')
    //             ->from('contributors');
		// $query->innerJoin('contributor_nodes', 'contributor_nodes.contributor_id = contributors.id' );
    //     $query->innerJoin('surveys', 'surveys.contributor_id = contributors.id' );
    //      $query->innerJoin('companies', 'companies.id = contributors.company_id' );
    //      $query->innerJoin('survey_quarters', 'survey_quarters.id = surveys.survey_quarter_id' );
    //     //  $query->innerJoin('data_fields', 'data_fields.survey_id = surveys.id' );
    //     $query->where(['survey_quarter_id' => $qid]);
    //     $query->andWhere(['IN', 'location_node_id', $locationnodesArr]);
    //     $query->andWhere(['surveys.completed'=>'1']);
    //     // $query->andWhere(['=', ['location_node_id' => 'data_fields']]);
    //     $query->orderBy('contributors.id');
    //
    //     $data =$query->all();
    //     $dataArray = [];
    //    //  echo '<pre/>';print_r($data);exit;
    //
    //     if(!empty($data)) {
    //     	foreach($arrNodes as $node) {
    //     		foreach($data as $val) {
		// 			if($node['location_node_id'] == $val['location_node_id']) {
		// 				$query = (new \yii\db\Query())->select('*')->from('data_fields');
		// 				$query->innerJoin('survey_template_question_nodes', 'data_fields.survey_template_question_node_id = survey_template_question_nodes.id');
		// 				$query->where(['data_fields.contributor_id'=> $val['contributor_id']]);
		// 				$query->andWhere(['survey_template_question_nodes.location_node_id'=> $node['location_node_id']]);
		// 				$query->andWhere(['data_fields.quarter'=> $val['quarter']]);
    //
		// 				$contributor_or_not = $query->one();
		// 				//$contributor_or_not = true; //  this should be removed when we optimize the query, because it takes too long
		// 				//print_r($contributor_or_not);
		// 				if($contributor_or_not){
		// 				  $dataArray[$node['id']][] = $val['contributor_code'];
		// 				}
		// 			  // print_r([$val]);
    //
    //     			}
    //     		}
    //     	}
    //     }
    //     //echo '<pre/>';print_r([$dataArray]);exit;
    //     return $dataArray;

    }

    ## -------Find All The Nodes ---------##
    public function getAllNodes($intQuestionId ,$visibility , $showAverage)
    {
    	$arrNodes = $parentArr = [];
    	## Fetch all the question nodes
            $arrparentNodes = SurveyTemplateQuestionNodes::find(['survey_template_question_nodes.*','position'])
                    ->where('survey_template_question_node_id IS NULL')
                    ->orWhere(['survey_template_question_node_id' => ''])
                    ->andWhere(['survey_template_question_id' => $intQuestionId ])
                    ->joinWith('locations')
                    ->orderBy('position')
                    ->asArray()
                    ->all();
             //echo '<pre/>';print_r($arrparentNodes);exit;
            if(!empty($arrparentNodes)) {
                foreach($arrparentNodes as $node) {

                    $arrchildNodes = [];
                    $arrchildNodes = SurveyTemplateQuestionNodes::find(['survey_template_question_nodes.*','position'])
                    ->where([
                    	'survey_template_question_id' => $intQuestionId ,
                    	'survey_template_question_node_id' => $node['id'],
                    	'included' => '1',
                    	'capture_level' => '1'
					])
                    ->joinWith('locations')
                    ->orderBy('position')
                    ->asArray()
                    ->all();
                    $node['calculation'] = '';
                    $node['childCount'] = count($arrchildNodes);
                    $arrNodes[] = $node;
                    //echo '<pre/>';print_r($arrNodes);
                    if(!empty($arrchildNodes)) {
                   $arrNodes= array_merge($arrNodes , $arrchildNodes);
                   if(($node['capture_level'] != "1" || $node['included'] != "1") && ($visibility == 'text' || $showAverage == 1) ) {
                   		$childArr = ArrayHelper::getColumn($arrchildNodes , 'id');
                   		$parentArr[$node['id']] = $childArr;
                   }
                   if($showAverage == 1) {
                   		$node['name'] = $node['name'].' Average';
                   		$node['calculation'] = 'average';
                   		$arrNodes []= $node;
               	   }

               }

                }
            }
            //echo '<pre/>';print_r($arrNodes);exit;
           return json_encode(['arrNodes' => $arrNodes ,  'parentArr' => $parentArr]);
    }

    ##--------Calculations for parents node-------##
    public function calculateParentMean($arrNodes ,$getMeanArr ,$arrDataIDS ,$getNArr ,$arrColumnValues ,$oldMeanArr , $oldNArr, $qid = null)
    {
       $quarterArray = SurveyQuarters::find()->where(['id' => $qid])->asArray()->one();
       $currentQuarter = $quarterArray['quarter'];

       $previousQuarter = $this->previousQuarter($currentQuarter);

      //
    	//echo '<pre/>';print_r($arrDataIDS);exit;
    	$parentArr = $oldParentArr = [];
    	foreach($arrNodes as $key=>$value) {
    		foreach($arrDataIDS as $id) {
          $contributions = [];
    			$deviationArr = $highestArr = [];
    			$n = $oldN =0;
    			$count = 0;
    			foreach($value as $val) {
    				if(isset($getMeanArr[$val][$id])) {
    					if(!isset($parentArr[$key][$id])) {
    						$parentArr[$key][$id]['mean'] =   $oldParentArr[$key][$id]['mean'] = 0;

    					} //echo $getMeanArr[$val][$id].'<br/>';
    					//echo $parentArr[$key][$id]['mean'].'+'.$getMeanArr[$val][$id];

            //  print_r([$qid /*quorter_id*/,$val /*node id*/ ,$id ,$currentQuarter ]);
              $searchModel = new DataFields();
              $dataProvider = $searchModel->getContributions($qid /*quorter_id*/ ,$val /*node id*/ ,$id ,$currentQuarter );
              foreach($dataProvider as $contribution){
                $contributions[] = $contribution['value'];
                $parentArr[$key][$id]['mean'] += $contribution['value'];
                $count++;
              }
      			//	$parentArr[$key][$id]['mean'] += $getMeanArr[$val][$id];
    					if(isset($oldMeanArr[$val][$id])) {
    						$oldParentArr[$key][$id]['mean'] += $oldMeanArr[$val][$id];
    						$oldN += $oldNArr[$val][$id];
    					}
    					$deviationArr[] = $getMeanArr[$val][$id];
    					$n += $getNArr[$val][$id];
    					//$count++;
    					$highestArr[] = $getMeanArr[$val][$id];

    				}
    			}
	    		## Calculate Standard deviation
	    		if(count($deviationArr) > 1) {
	    			$parentArr[$key][$id]['sd'] = $this->standard_deviation($deviationArr);
	    		}
	    		//$parentArr[$key][$id]['gross'] =  $this->single_gross_value();
        //  echo "<pre/>";
        //  print_r(array_sum($contributions)/count($contributions));
        //  echo PHP_EOL;
        //  print_r($contributions);
        //  die();
	    		if($n >0 && $count > 0) {
	    			$parentArr[$key][$id]['n']  = $n;//echo 'df'.$parentArr[$key][$id]['mean'];exit;
	    			$parentArr[$key][$id]['mean'] = $parentArr[$key][$id]['mean']/ $count;
	    		}
	    		if($oldN >0) {
	    			$oldParentArr[$key][$id]['mean'] = $oldParentArr[$key][$id]['mean']/ $oldN;
	    		}
	    		if(count($highestArr) >0) {
	    			$parentArr[$key][$id]['highest'] = max($highestArr);
	    		}

  //        print_r($contributions);
//          print_r(array_sum($contributions));
    		}

    		foreach($arrColumnValues as $k => $val) {
    			if($val['value'] == 'Gross Income Yields') {
    				$return_id = $val['return_column_id'];
    				$data_id = $val['data_field_template_id'];
    				if(isset($parentArr[$key][$data_id]['mean']) && isset($parentArr[$key][$return_id]['mean'])) {
    					$parentArr[$key][$val['data_field_template_id']]['gross'] = (($parentArr[$key][$val['return_column_id']]['mean'] *12) / $parentArr[$key][$val['data_field_template_id']]['mean']) * 100;
    				}
    			}

    			if($val['value'] == 'Current Q - Last Q') {
    				$data_id = $val['data_field_template_id'];
    				if(isset($parentArr[$key][$data_id]['mean']) && isset($oldParentArr[$key][$data_id]['mean'])) {
    					$parentArr[$key][$val['data_field_template_id']]['current'] = ($parentArr[$key][$data_id]['mean'] - $oldParentArr[$key][$data_id]['mean']);
    				}
    			}



    		}
    	}
      // echo '<pre/>';print_r($parentArr);exit;
    	return $parentArr;
    }


    ##---------Calculate Standard Deviation-----##
    public function standard_deviation($sample)
    {
		if(is_array($sample)){
			$mean = array_sum($sample) / count($sample);
			foreach($sample as $key => $num) $devs[$key] = pow($num - $mean, 2);
			return sqrt(array_sum($devs) / count($devs));
		} else {
			return false;
		}
	}

    ##------ Calculate the gross income yields ----------##
	public function getGrossIncomeYields($arrColumnValues , $arrMean , $arrNodes)
	{
		$grossArr = [];
		foreach($arrColumnValues as $val) {
			if($val['value'] == 'Gross Income Yields') {
				foreach($arrNodes as $node) {
					if(isset($arrMean[$node['id']][$val['return_column_id']]) && isset($arrMean[$node['id']][$val['data_field_template_id']])) {
						$grossArr[$node['id']][$val['data_field_template_id']] =  (($arrMean[$node['id']][$val['return_column_id']] * 12) / $arrMean[$node['id']][$val['data_field_template_id']]) * 100;
					}
				}

			}
		} //echo '<pre/>';print_r($arrColumnValues);exit;
		return $grossArr;
	}

	##--- Function to get Previous Quarter  ----##
    public function previousQuarter($surveyQuarter)
    {
        $arrQuarter = explode(':',$surveyQuarter);
        $previousQuarter = '';
        if($arrQuarter[1] > 1) {
            $previousQuarter =$arrQuarter[0].':'.($arrQuarter[1] -1);
        } else {
            $previousQuarter =($arrQuarter[0]-1).':4';
        }
        return $previousQuarter;
    }

    ##-----Function to get remaining Quarters-----##
    public function remainingQuarter($surveyQuarter)
    {
    	$surveyQuarter = explode(':',$surveyQuarter);
        $remainingQuarter = [];
        $arrQuarter = ['1','2','3','4'];
        foreach($arrQuarter as $quart) {
        	if($surveyQuarter[1] != $quart) {
            	$remainingQuarter[] =$surveyQuarter[0].':'.$quart;
        	}
        }
        return $remainingQuarter;
    }

    ## Function to get last quarter values
    public function getPreviousQuarter($quarter , $template_id ,$arrDataIDS , $arrNodeIDS)
    {
    	$previousQuarter = $this->previousQuarter($quarter);
        $intQuarterId = '';
        $arrMean = $arrDatafields= $arrN = [];
        $arrDatafields = (new \yii\db\Query())
        		 ->select(['avg(value) as average_val','survey_template_question_node_id','data_field_template_id','count(surveys.id) as tot_cnt'])
                 ->from('surveys')
                 //->joinwith('datafields')
                 ->leftJoin('data_fields', 'data_fields.survey_id = surveys.id' )
                 ->leftJoin('survey_quarters', 'survey_quarters.id = surveys.survey_quarter_id' )
                 ->where([ 'survey_template_id' => $template_id , 'survey_quarters.quarter' => $previousQuarter])
                 ->andWhere(['<>','value',''])
                 ->andWhere(['included'=> '1'])
                 ->andWhere(['IN', 'survey_template_question_node_id', $arrNodeIDS])
                 ->groupBy(['survey_template_question_node_id','data_field_template_id'])
                 ->orderBy('')
                 ->all();

        if(!empty($arrDatafields)) {
        	  ## Mean Array
               $arrMean = ArrayHelper::map($arrDatafields , 'data_field_template_id','average_val', 'survey_template_question_node_id');
       			## N array
               $arrN = ArrayHelper::map($arrDatafields , 'data_field_template_id','tot_cnt', 'survey_template_question_node_id');
            }


        return json_encode([ 'arrOldMean' => ($arrMean) ,  'arrOldN' => $arrN]);
    }

    ##------Individual Contributions -------##
    public function actionContribution($quarter_id ,$filter_id ,$node_id)
    {
    	$data_filed_id = $currentQuarter = '';
    	$filterArray = FieldFilters::find()->where(['id' => $filter_id])->asArray()->one();
    	$quarterArray = SurveyQuarters::find()->where(['id' => $quarter_id])->asArray()->one();
    	if(!empty($filterArray)) {
    		$data_filed_id = $filterArray['data_field_template_id'];
    		$currentQuarter = $quarterArray['quarter'];
    	}
    	$previousQuarter = $this->previousQuarter($currentQuarter);
    	$remainingQuarter = $this->remainingQuarter($currentQuarter);
    	$searchModel = new DataFields();
        $dataProvider = $searchModel->individualContributions($quarter_id ,$filter_id ,$node_id ,$data_filed_id ,$currentQuarter ,$previousQuarter);
        return $this->render('individual-contributions',[
            'dataProvider'  => $dataProvider,
            'filterArray' => $filterArray,
            'currentQuarter' => $currentQuarter,
            'quarter_id' => $quarter_id,
            'survey_id' => $quarterArray['survey_template_id']
            ]);
    }

    ##------Exclude contributors answer--------##
    public function actionExcludeanswer()
    {
    	$arrPostedData = Yii::$app->request->post();
    	if($arrPostedData['included'] == 1) {
    		$boolIncluded = 0;
    		$strReason = $arrPostedData['exclude_reason'];
    	} else {
    		$boolIncluded = 1;
    		$strReason = '';
    	}
    	$connection = Yii::$app->db;
		$boolAns = $connection ->createCommand()
 				->update('data_fields', ['included' => $boolIncluded, 'exclude_reason' => $strReason],
 					'id ='.$arrPostedData['data_field_id'])
 		    	->execute();
 		 ## If updated
 		 $session = Yii::$app->session;
 		 if($boolAns) {
			$session->setFlash("success",DATA_UPDATE_SUCC);
 		 } else {
 		 	$session->setFlash("error",DATA_UPDATE_ERR);
 		 }
 		return $this->redirect(Yii::$app->request->referrer);
    }

    ##-------Calculate a,b,r values--------##
    public function getabrvalues($arrNodes ,$getMeanArr ,$arrDataIDS)
    {//echo '<pre/>';print_r($getMeanArr);exit;
       $logSizeArr = $logRentalArr = $averageRentalArr = $averageSizeArr = $resultArr =[];
    	foreach($arrNodes as $id) {
    		$averageRentalArr[$id]['sum'] = $averageSizeArr[$id]['sum'] = 0;
    		$n= 0;
    		foreach($arrDataIDS as $key =>$val) {
    			$intSize = '';
    			$intSize = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
    			if($intSize != "") {
    				if(isset($getMeanArr[$id][$key]) && $getMeanArr[$id][$key]!="") {
    					## Calculate log of size in sqm & mean value
    					$logSize = log($intSize);
    					$logValue = log($getMeanArr[$id][$key]);
    					$logSizeArr[$id][$key] = $logSize;
    					$logRentalArr[$id][$key] = $logValue;
    					$averageRentalArr[$id]['sum'] += $logRentalArr[$id][$key];
    					$averageSizeArr[$id]['sum'] +=  $logSizeArr[$id][$key];
    					$n++;

    				}
    			}

    		}
    		## calculate average of log values ,log sizes
    		if($n != 0) {
    			$averageRentalArr[$id]['average'] = $averageRentalArr[$id]['sum'] / $n;
    			$averageSizeArr[$id]['average'] = $averageSizeArr[$id]['sum'] / $n;
    	    } else {
    	    	$averageRentalArr[$id]['average'] = $averageRentalArr[$id]['sum'] ;
    			$averageSizeArr[$id]['average'] = $averageSizeArr[$id]['sum'] ;
    	    }
    		$sumRentalArr[$id]['sum'] = $sumSizeArr[$id]['sum'] = $totalRentalArr[$id]['sum'] =0;
    		$resultArr[$id]['b'] = $resultArr[$id]['a'] = $resultArr[$id]['r'] ='';
    		foreach($arrDataIDS as $key =>$val) {
    			if(isset($logSizeArr[$id][$key]) && isset($averageRentalArr[$id]['average']) && isset($logRentalArr[$id][$key]) && isset($averageSizeArr[$id]['average'])) {
    				$XminusAverageSize[$id][$key] = $logSizeArr[$id][$key] - $averageSizeArr[$id]['average'];
    				$YminusAverageVal[$id][$key] = $logRentalArr[$id][$key] - $averageRentalArr[$id]['average'];
    				$valMultiSize = $YminusAverageVal[$id][$key] * $XminusAverageSize[$id][$key];
    				$SizeSquare = $XminusAverageSize[$id][$key] * $XminusAverageSize[$id][$key];
    				$ValSquare = $YminusAverageVal[$id][$key] * $YminusAverageVal[$id][$key];
    				$totalRentalArr[$id]['sum'] += $valMultiSize;
    				$sumSizeArr[$id]['sum'] +=  $SizeSquare;
    				$sumRentalArr[$id]['sum'] +=  $ValSquare;

    			}

    		}
    		if($sumSizeArr[$id]['sum'] != 0) {
    			$resultArr[$id]['b'] = $totalRentalArr[$id]['sum'] / $sumSizeArr[$id]['sum'];
    	    }
    	    $resultArr[$id]['a'] = $averageRentalArr[$id]['average'] -($resultArr[$id]['b'] * $averageSizeArr[$id]['average']);
    	    ## Calculate r values
          if($n < 0){
            $RentalSD = sqrt($sumRentalArr[$id]['sum'] / ($n-1));
            $SizeSD = sqrt($sumSizeArr[$id]['sum'] / ($n-1));
          }else{
            $RentalSD = sqrt($sumRentalArr[$id]['sum']);
            $SizeSD = sqrt($sumSizeArr[$id]['sum']);
          }
    	    $finalValue= $finalAverage = 0;
    	    foreach($arrDataIDS as $key =>$val) {
    	    	if(isset($XminusAverageSize[$id][$key]) && $YminusAverageVal[$id][$key]) {
    	    		$sizeValue = ($SizeSD>0) ? ($XminusAverageSize[$id][$key] / $SizeSD) : $XminusAverageSize[$id][$key];
    	    		$rentalValue = ($RentalSD>0) ? ($YminusAverageVal[$id][$key] / $RentalSD) : $YminusAverageVal[$id][$key];
    	    		$finalValue = $sizeValue * $rentalValue;
    	    		$finalAverage += $finalValue;
    	    	}
    	    }
          if($n < 0){
            $rValue = $finalAverage/ ($n-1);
          }else{
            $rValue = $finalAverage;
          }
    	    $resultArr[$id]['r'] = $rValue * $rValue;

    	}
    	return $resultArr;
    }
};
