<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : PropertiesController.php
# Created on : 12th Aug 2015 by Bakhtawar Khan
# Update on : 12th Aug 2015 by Bakhtawar Khan
# Purpose : This page will perform CRUD on property types.
*/
namespace app\controllers\admin;

use Yii;
use app\models\User;
use app\models\PropertyTypes;
use app\models\SearchPropertyTypes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\Expression;
use app\components\AccessRule;
use yii\filters\AccessControl;

/**
 * PropertiesController implements the CRUD actions for PropertyTypes model.
 */
class PropertiesController extends Controller
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
                'only' => ['view', 'create', 'index', 'update', 'delete'],
                'rules' => [
                   [
                        'actions' => ['view', 'index', 'update', 'delete', 'create'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
						'denyCallback' => function ($rule, $action) {
						 	throw new ForbiddenHttpException('У Вас нет доступа');
						 },

                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all PropertyTypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchPropertyTypes();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PropertyTypes model.
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
     * Creates a new PropertyTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PropertyTypes();
		## Set Scenario
		$model->scenario = 'create';
		$session = Yii::$app->session;
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			## Set default values
			$model->addedDate = new Expression('NOW()');
			$model->updateDate = new Expression('NOW()');
			$model->status = 'Active';
			 if ($model->save()) { 
				 $session->setFlash('success', PROPTYPE_ADD_SUCC);
            } else {
				$session->setFlash('error', PROPTYPE_ADD_ERR);
			}
         return $this->redirect(['index']);
        	
		}
        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing PropertyTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$session = Yii::$app->session;
		## Set Scenario
		$model->scenario = 'update';
		$currStatus = $model->status;
		if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			$model->updateDate = new Expression('NOW()');
			if($model->status == '') {
				$model->status = $currStatus;
			}
			if ($model->save()) {
				 $session->setFlash('success', PROPTYPE_UPDATE_SUCC);
            } else {
				$session->setFlash('error', PROPTYPE_UPDATE_ERR);
			}
			return $this->redirect(['index']);
		}
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PropertyTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        //$this->findModel($id)->delete();
		$intId = Yii::$app->request->post('id');
		$session = Yii::$app->session;
		$model = $this->findModel($intId);
		$model->status = 'Deleted';
		if($model->save()) {
			$session->setFlash('success', PROPTYPE_DEL_SUCC);
		} else {
			$session->setFlash('error', PROPTYPE_DEL_ERR);
			//print_r($model->getErrors());exit;
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the PropertyTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PropertyTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
