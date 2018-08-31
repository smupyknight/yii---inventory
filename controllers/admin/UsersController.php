<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : usersController.php
# Created on : 17th Aug 2015 by Suraj M
# Update on : 31st Sep 2015 by Bakhtawar K
# Purpose : This page will perform CRUD on users.
*/
namespace app\controllers\admin;

use Yii;
use app\models\Rodeusers;
use app\models\Contributors;
use app\models\Companies;
use app\models\LocationNodes;
use app\models\Rodeuserssearch;
use app\models\Contributornodes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * RodeusersController implements the CRUD actions for Rodeusers model.
 */
class UsersController extends Controller
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
                'only' => ['view', 'create', 'index', 'update', 'delete','assignnode', 'viewnodes', 'removenode'],
                'rules' => [
                   [
                        'actions' => ['view', 'index', 'update', 'delete', 'create', 'assignnode', 'viewnodes', 'removenode'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                 ],
            ],
        ];

    }



    /**
     * Lists all Rodeusers models.
     * @return mixed
     */
    public function actionIndex($company_id='')
    {
    		$this->layout = 'lay-admin';
        $searchModel = new Rodeuserssearch();
        $companies = Companies::find()->orderBy('name')->all();
        $companies = ArrayHelper::map($companies, 'id', 'name');
        $companies[""] = "All";
    		/*-------added below 2 lines for passing query parameters--------*/
    		$queryParams= Yii::$app->request->getQueryParams();
    		if(!empty($companyid))
    		{
    			$queryParams["Rodeuserssearch"]["company_id"] = $company_id ;
    		}
    		/*-------added below 2 lines for passing query parameters--------*/

        $dataProvider = $searchModel->search($queryParams);
        //echo "<pre/>";print_r($dataProvider->getModels());exit;
        return $this->render('index', [
            'companies' => $companies,
            'company' => $company_id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rodeusers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view_user', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rodeusers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$session = Yii::$app->session;
    $model = new Rodeusers();

		$contributormodel = new Contributors();
		$contributormodel->scenario	=	'add';
		$model->scenario			=	'add';
		$model->created_at	=	date('Y-m-d h:i:s');
		$model->role = 'Contributor';
		$model->auth_key = \Yii::$app->security->generateRandomString();
    if ($model->load(Yii::$app->request->post()) && $contributormodel->load(Yii::$app->request->post()) && $model->save()) {
			//
  			$lastInsertID = $model->id;
  			if(!empty($lastInsertID) && $lastInsertID)
  			{
  				$contributormodel->user_id=$lastInsertID;

  				if($contributormodel->save())
  				{
  					$session->setFlash('success',CREATE_USER);
  					return $this->redirect(['index']);
  				}
  			}
      } else {
            return $this->render('add_contributor', [
                'model' => $model,
	              'contrimodel'=>$contributormodel
            ]);
      }
    }

    /**
     * Updates an existing Rodeusers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$connection = \Yii::$app->db;
		$session = Yii::$app->session;
		$this->layout = 'lay-admin';
    $model = $this->findModel($id);
    $newModel = $this->findModel($id);
    $userModel = $this->findModel($id);
    //$model->scenario = 'updateemail';
    $newModel->scenario = 'updatepassword';
    $userModel->scenario = 'changelogin';
		if($model->auth_key == "") {
			$model->auth_key = \Yii::$app->security->generateRandomString();
		}
		if($newModel->auth_key == "") {
			$newModel->auth_key = \Yii::$app->security->generateRandomString();
		}
		//$newModel->password = '';
		$arrpost	=	Yii::$app->request->post();
		//print_r(Yii::$app->request->post());exit;
        if (isset($arrpost['change-email']) && $model->load(Yii::$app->request->post()) && $model->save())
		{

			$intStatus	=	$arrpost['Rodeusers']['disabled'];
			if($intStatus!="")
			{
				$qryUpdateUser 			=	$connection->createCommand("UPDATE contributors SET disabled='".$intStatus."' WHERE user_id=".$id);
				$resultUpdateUser		=	$qryUpdateUser->execute();//echo $resultUpdateUser;exit;
				$session->setFlash('success',EDIT_USER_PROFILE);
	           	return $this->redirect(['update', 'id' => $model->id]);
			}
		} else if (isset($arrpost['change-password']) &&  $newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			$newModel->password = md5($newModel->new_password);
			$newModel->updated_at = new Expression('NOW()');
			if($newModel->save()) {
				$session->setFlash('success', PASSWORD_EDIT_SUCC);
			} else { //print_r($newModel->getErrors());exit;
				$session->setFlash('error', PASSWORD_EDIT_ERR);
			}
			return $this->redirect(['update', 'id' => $newModel->id]);
		} else if (isset($arrpost['change-login']) && $userModel->load(Yii::$app->request->post()) && $userModel->validate())
		{

			$newuser	=	$userModel->login;
			if($newuser!="" && preg_match('/^[a-z0-9]\w{4,}$/i', $newuser))
			{
				$checkuser = $connection->createCommand("SELECT login FROM users WHERE login='".$newuser."' LIMIT 1");
				$exist = $checkuser->execute();

				$userModel->login = $newuser;
				if(!$exist && $userModel->save()) {
					$session->setFlash('successu', UNAME_EDIT_SUCC);
				} else {
					if ($exist) {
						$session->setFlash('erroru', UNAME_EDIT_EXIST);
					} else {
						$session->setFlash('erroru', UNAME_EDIT_ERR);
					}
				}
	           	return $this->redirect(['update', 'id' => $model->id]);
			} else {
				$session->setFlash('erroru', UNAME_EDIT_BF);
			}
		}
		return $this->render('update', ['model' => $model , 'newModel' => $newModel]);
    }

    /**
     * Deletes an existing Rodeusers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
  		$session = Yii::$app->session;
  		$connection = \Yii::$app->db;
  		$id	=	Yii::$app->request->post('id');
          //$this->findModel($id)->delete();
  		if(!empty($id))
  		{
  			$qryUpdateUser 			=	$connection->createCommand('UPDATE users SET disabled=2 WHERE id='.$id);
  			$resultUpdateUser		=	$qryUpdateUser->execute();
  			if($resultUpdateUser)
  			{
  				$qryUpdateContributor			  =	$connection->createCommand('UPDATE contributors SET disabled=2 WHERE user_id='.$id);
  				$resultUpdateContributor		=	$qryUpdateContributor->execute();
  				if($resultUpdateContributor)
  				{
  					$session->setFlash('success',DELETE_USER_SUCCESS);
  				}
  			}
  		}
      return $this->redirect(['index']);
    }

  public function actionRemovenode($id){
    if(isset($_GET['contributor_id'])){
      $connection = \Yii::$app->db;

      $deletePrevioudNodes	=	$connection->createCommand()->delete('contributor_nodes', ['property_type_id' => $id, 'contributor_id' => $_GET['contributor_id']])->execute();
      $arrayResponse = [
        'result' => 'success',
        'message' => 'Node was successfully deleted!'
      ];
      return json_encode($arrayResponse);
    }
  }


	public function actionAssignnode($id)
	{ 
		$session = Yii::$app->session;
		$this->layout = 'lay-admin';

    $model = $this->findModel($id);
		$arrPost	=	yii::$app->request->post();
		$arrLocationNodes	=	array();
		$arrParentNode		=	array();
		$arrChildNode		=	array();
		$arrTotalNodes		=	array();
		$arrOldPropertyId	=	Contributornodes::find()
		->select('property_type_id')
		->where(['contributor_id' => $id])
		->orderBy('property_type_id')
		->asArray()
		->one();
  		// $intOldPropertyId = $arrOldPropertyId['property_type_id'];
      // print_r([$arrPost, $arrLocationNodes, $arrOldPropertyId]);
      // die();
		//print_r($intOldPropertyId);exit;
		if(isset($arrPost) && !empty($arrPost))
		{

			if(isset($arrPost['filter']) && $arrPost['filter']=='filter_property')
			{
				$model->load(Yii::$app->request->post());
			 	$intOldPropertyId = $intPropertyId		=	$arrPost['Rodeusers']['id'];
				$arrLocationNodes	=	LocationNodes::find()->where(['property_type_id' => $intPropertyId])->all();
			}
			elseif(isset($arrPost['assign']) && $arrPost['assign']=='assign_nodes')
			{
				//$contributorId	=	$model->id; //suraj
				$contributorId	= $arrPost['cont_id']; // bak bak bak
				//print_r($arrPost);exit;
				$propertyId		=	$arrPost['Rodeusers']['id'];
				if(isset($arrPost['chk_node']) && count($arrPost['chk_node'])>0)
				{
					$arrParentNode	=	$arrPost['chk_node'];
				}

				if(isset($arrPost['chk_childnode']) && count($arrPost['chk_childnode'])>0)
				{
					$arrChildNode	=	$arrPost['chk_childnode'];
				}

				$arrTotalNodes	=	array_merge($arrParentNode,$arrChildNode);

				if(count($arrTotalNodes)>0)
				{
					$i=0;
					$connection = \Yii::$app->db;

					$deletePrevioudNodes	=	$connection->createCommand()->delete('contributor_nodes', ['property_type_id'=>$propertyId,'contributor_id'=>$contributorId])->execute();
          //echo $deletePrevioudNodes;exit;
					//var_dump($deletePrevioudNodes);
					//echo '*'.$connection->createCommand()->getRawSql();exit;
					foreach($arrTotalNodes as $val)
					{
						$k =$connection->createCommand()->insert('contributor_nodes', ['location_node_id'=>$val,'contributor_id' => $contributorId,'property_type_id'=>$propertyId])->execute();
						$i++;
					}//print_r($arrTotalNodes);exit;
					if($i==count($arrTotalNodes))
					{
						$session->setFlash('success',NODE_ASSIGN_SUCCESS);
						return $this->redirect(['admin/users/index']);
					}
				}

			}
		}
		return $this->render('assign_node',[
  		'model'=>$model,
  		'locationnodes'=>$arrLocationNodes,
  		'intPropertyId'=>$intOldPropertyId
		]);//'intPropertyId'=>$intPropertyId
	}

	public function actionInitnode($id,$contributor_id,$node='')
	{
		$arrAssignNodes	=	array();
		if(!empty($contributor_id))
		{
			$sqlGetAssignNodes = Contributornodes::find()->select('location_node_id')->where(['contributor_id' => $contributor_id, 'property_type_id' => $id])->asArray()->all();
			if(is_array($sqlGetAssignNodes) && count($sqlGetAssignNodes)>0)
			{
				foreach($sqlGetAssignNodes as $key=>$val)
				{
					$arrAssignNodes[]	=	$val['location_node_id'];
				}
			}

			if(!empty($node))
			{
				$id	=	$node;
			}
			$createdarr	=	array();
			$arrLocationNodes	=	array();
			$arrLocationNodes	=	LocationNodes::find()->where(['property_type_id' => $id])->orderBy('name')->all();
			$i=1;
			if(count($arrLocationNodes) >0) {
			foreach($arrLocationNodes as $val)
			{
				$strSelect	='';
				$strClass = "collapsed";
				if(in_array($val->id,$arrAssignNodes))
				{
					$strSelect	=	'checked=checked';
					$strClass = 'expanded';
				}
				$createdarr[$i]['span']['html']		=	'<input type="checkbox" '.$strSelect.' name=chk_node[] value='.$val->id.'>'.$val->name;
				$createdarr[$i]['li']['class']		=	$strClass;
				$createdarr[$i]['li']['id']			=	$val->id;
				$i++;
			}

			echo json_encode(['nodes'=>$createdarr]);
			} else {
				echo "<script type='text/javascript'>

    alert('Content Loaded');

</script>";
			}
		}
		else
		{
			echo json_encode(['nodes'=>'something went wrong']);
		}
	}

	public function actionChildnode($id,$contributor_id,$node='')
	{
		$arrAssignNodes	=	array();
		if(!empty($contributor_id))
		{
			$sqlGetAssignNodes = Contributornodes::find()->select('location_node_id')->where(['contributor_id' => $contributor_id, 'property_type_id' => $id])->asArray()->all();
			if(is_array($sqlGetAssignNodes) && count($sqlGetAssignNodes)>0)
			{
				foreach($sqlGetAssignNodes as $key=>$val)
				{
					$arrAssignNodes[]	=	$val['location_node_id'];
				}
			}

			if(!empty($node))
			{
				$id	=	$node;
			}
			$createdarr	=	array();
			$arrLocationNodes	=	array();
			if(!empty($id))
			{
				$arrLocationNodes	=	LocationNodes::find()->where(['location_node_id' => $id])->all();
				$i=0;
				if(!empty($arrLocationNodes))
				{
					foreach($arrLocationNodes as $val)
					{
						$strSelect	=	'';
						$strClass = "collapsed";
						if(in_array($val->id,$arrAssignNodes))
						{
							$strSelect	=	'checked=checked';
							$strClass = 'expanded';
						}
						$createdarr[$i]['span']['html']		=	'<input type="checkbox" '.$strSelect.' name=chk_childnode[] value='.$val->id.'>'.$val->name;
						$createdarr[$i]['li']['class']		=	$strClass;
						$createdarr[$i]['li']['id']			=	$val->id;
						$i++;
					}
				}
			}

			echo json_encode(['nodes'=>$createdarr]);
		}
		else
		{
			echo json_encode(['nodes'=>'something went wrong']);
		}
	}



    /**
     * Finds the Rodeusers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rodeusers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rodeusers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('User hasn\'t any model.');
        }
    }

	## ------ Edit Admin Profile ----##
	public function actionEditprofile()
	{
		$session = Yii::$app->session;
		$id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
		$model->scenario = 'profile';
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			if($model->save()) {
				$session->setFlash('success', PROFILE_EDIT_SUCC);
			} else {
				$session->setFlash('error', PROFILE_EDIT_ERR);
			}
		}
		return $this->render('edit-profile', ['model' => $model]);
	}

	## -------- Change Password -----##
	public function actionChangepassword()
	{
		$session = Yii::$app->session;
		$id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
		$model->scenario = 'changepassword';
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			$model->password = md5($model->new_password);
			if($model->save()) {
				$session->setFlash('success', PASSWORD_EDIT_SUCC);
			} else {
				$session->setFlash('error', PASSWORD_EDIT_ERR);
			}
			return $this->redirect(['changepassword']);
		}
		return $this->render('change-password', ['model' => $model]);
	}

	## -------- Generate Password -----##
	public function actionGeneratepassword(){
		$model = new User();
		//$allUsers = $model->generatePassword();
		$users = User::find()
    	->orderBy('id')
		  ->select(['id','email'])
    	->all();
		foreach($users as $user){
			$model->generatePassword($user);
		}

		//echo '<pre/>';
		//print_r($customers);
		//exit;
	}

	##----------View Nodes---------##
	public function actionViewnodes($id)
	{
		$arrLocationNodes	=	Contributornodes::find()
		->select(['property_types.name as property','contributor_nodes.property_type_id'])
		->where(['contributor_id' => $id, 'property_types.status' => 'Active'])
		->innerJoinWith(['propertytypes'])
		->orderBy('contributor_nodes.property_type_id')
		->groupBy('contributor_nodes.property_type_id')
		->asArray()
		->all();
		//$arrLocationNodes =ArrayHelper::map($objLocationNodes,'property','name','property_type_id');
		//echo '<pre/>';print_r($arrLocationNodes);exit;

		return $this->render('view-nodes',[
			'locationNodes' => $arrLocationNodes,
			'contributor_id' => $id
			]);
	}
}
