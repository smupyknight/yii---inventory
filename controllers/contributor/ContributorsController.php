<?php


namespace app\controllers\contributor;

use Yii;
use app\models\contributor\Contributors;
use app\models\contributor\Contributorsserach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;


/**
 * ContributorsController implements the CRUD actions for Contributors model.
 */
class ContributorsController extends Controller
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
                        'roles' => [User::ROLE_CONTRIBUTOR],
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all Contributors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Contributorsserach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contributors model.
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
     * Creates a new Contributors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contributors();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Contributors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {

		if(isset(Yii::$app->user->identity->id) && !empty(Yii::$app->user->identity->id))
		{
			$id	=	Yii::$app->user->identity->id;
		}
		else
		{
			return $this->redirect(['site/login']);
		}
		$session = Yii::$app->session;
		$objGetprimaryId	=	Contributors::find()->where(['user_id' => $id])->one();
		if(!empty($objGetprimaryId))
		{
			$primaryId	=	$objGetprimaryId->id;
		}
		else
		{
			return $this->redirect(['site/login']);
		}
		
        $model = $this->findModel($primaryId);
		$model->scenario	=	'edit';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		   $session->setFlash('success',EDIT_USER_PROFILE);
           return $this->redirect(['contributor/contributors/update']);
        } else {
            return $this->render('edit_user', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Contributors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contributors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contributors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contributors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
