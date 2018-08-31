<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : EmailTemplatesController.php
# Created on : 14th Aug 2015 by Bakhtawar Khan
# Update on : 15th Aug 2015 by Bakhtawar Khan
# Purpose : This page will perform CRUD on email templates.
*/
namespace app\controllers\admin;

use Yii;
use app\models\EmailTemplates;
use app\models\SearchEmailTemplates;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\Expression;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;

/**
 * EmailTemplateController implements the CRUD actions for EmailTemplates model.
 */
class EmailtemplatesController extends Controller
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
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all EmailTemplates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchEmailTemplates();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmailTemplates model.
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
     * Creates a new EmailTemplates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmailTemplates();
		## Set scenario
		$model->scenario = 'create';
		$session = Yii::$app->session;
		//echo '<pre/>';print_r(Yii::$app->request->post());echo strlen(Yii::$app->request->post('body'));exit;
        if ($model->load(Yii::$app->request->post())) {
			## ajax call performed to check unique name
			if (Yii::$app->request->isAjax) {
    		    Yii::$app->response->format = Response::FORMAT_JSON;
       		    return ActiveForm::validate($model);
			}
			$model->status = 'Active';
			$model->created_at = new Expression('NOW()');
			$model->updated_at = new Expression('NOW()');
			if ($model->save()) { 
				 $session->setFlash('success', TEMPLATE_ADD_SUCC);
            } else {
				$session->setFlash('error', TEMPLATE_ADD_ERR);
			}
         return $this->redirect(['index']);
        } 
		return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing EmailTemplates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->scenario = 'update';
		$session = Yii::$app->session;
		$currStatus = $model->status;
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
				 $session->setFlash('success', TEMPLATE_UPDATE_SUCC);
            } else {
				$session->setFlash('error', TEMPLATE_UPDATE_ERR);
			}
			return $this->redirect(['index']);
		}
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EmailTemplates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $intId = Yii::$app->request->post('id');
		$session = Yii::$app->session;
		$model = $this->findModel($intId);
		$model->status = 'Deleted';
		if($model->save()) {
			$session->setFlash('success', TEMPLATE_DEL_SUCC);
		} else {
			$session->setFlash('error', TEMPLATE_DEL_ERR);
			//print_r($model->getErrors());exit;
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the EmailTemplates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmailTemplates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmailTemplates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
