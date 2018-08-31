<?php


namespace app\controllers\contributor;

use Yii;
use app\models\contributor\Rodeusers;
use app\models\contributor\Contributors;
use app\models\contributor\Rodeuserssearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;

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
                'only' => ['update', 'changepassword'],
                'rules' => [
                   [
                        'actions' => ['update','changepassword'],
                        'allow' => true,
                        'roles' => [User::ROLE_CONTRIBUTOR],
                    ],
                 ],
            ],
			
        ];
		
    }
	
	
    /**
     * Updates an existing Rodeusers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {

		if(isset(Yii::$app->user->identity->id) && !empty(Yii::$app->user->identity->id))
		{
			$id	=	Yii::$app->user->identity->id;
			$connection = \Yii::$app->db;
			$session = Yii::$app->session;
			$this->layout = 'lay-admin';
			$model = $this->findModel($id);
			$model->scenario 	=	'edit';
			if ($model->load(Yii::$app->request->post()) && $model->save())
			{
				$session->setFlash('success',CHANGE_EMAIL_SUCCESS);
				return $this->redirect(['update']);
			}
		}
		else
		{
			return $this->redirect(['site/login']);
		}
				return $this->render('update', ['model' => $model]);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
			} else {//print_r($model->getErrors());exit;
				$session->setFlash('error', PASSWORD_EDIT_ERR);
			}
			return $this->redirect(['changepassword']);  
		}
		return $this->render('change-password', ['model' => $model]);
	}
}
