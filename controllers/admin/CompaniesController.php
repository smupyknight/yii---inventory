<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : AddTheme.php
# Created on : 18th Aug 2015 by Suraj M
# Update on : 18th Sep 2015 by Suraj M
# Purpose : This page will perform CRUD on companies.
*/
namespace app\controllers\admin;

use Yii;
use app\models\Companies;
use app\models\Companiessearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;

/**
 * CompaniesController implements the CRUD actions for Companies model.
 */
class CompaniesController extends Controller
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
     * Lists all Companies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Companiessearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Companies model.
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
     * Creates a new Companies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$session = Yii::$app->session;
        $model = new Companies();
		$model->display_code	=	'1';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$session->setFlash('success',CREATE_COMPANY);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Companies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$session->setFlash('success',UPDATE_COMPANY);
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('edit_company', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Companies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
		$session = Yii::$app->session;
		$id	=	Yii::$app->request->post('id');
		if(!empty($id))
		{
        	$this->findModel($id)->delete();
			$session->setFlash('success',DELETE_COMPANY);
		}
	        return $this->redirect(['index']);
    }

    /**
     * Finds the Companies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Companies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Companies::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
