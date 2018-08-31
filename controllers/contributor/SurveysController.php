<?php


namespace app\controllers\contributor;

use Yii;
use app\models\contributor\Surveys;
use app\models\contributor\Surveyssearch;
use app\models\contributor\Contributors;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;

/**
 * SurveysController implements the CRUD actions for Surveys model.
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
                'only' => ['index'],
                'rules' => [
                   [
                        'actions' => ['index'],
                        'allow' => true,
						'roles' => [User::ROLE_CONTRIBUTOR],
                        //'roles' => ['@'],
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all Surveys models.
     * @return mixed
     */
    public function actionIndex()
    {//echo md5('login123');
		$this->layout = 'lay-admin';
		if(isset(Yii::$app->user->identity->id) && !empty(Yii::$app->user->identity->id))
		{
			$id	=	Yii::$app->user->identity->id;
		}
		$objGetContributorId	=	Contributors::find()->where(['user_id' => $id])->one();
		if(!empty($objGetContributorId))
		{
			$ContributorId	=	$objGetContributorId->id;
		}
		else
		{
			return $this->redirect(['site/login']);
		}

        $searchModel = new Surveyssearch();
		$arrQuarterList	=	$searchModel->getQuartersList();

		/*-----added this two lines to send parameter to query -----*/

		$queryParams= Yii::$app->request->getQueryParams();
		if(!empty($ContributorId))
		{
			$queryParams["Surveyssearch"]["contributor_id"] = $ContributorId ;
		}

		$searchQuarter = '';
		/*-----added this two lines to send parameter to query -----*/
        if(!isset(Yii::$app->request->queryParams['Surveyssearch']['quarter'])) {
            $searchQuarter = array_values($arrQuarterList)[0];
        }
        $dataProvider = $searchModel->search($queryParams, $searchQuarter, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'quarters'=>$arrQuarterList,
        ]);
    }

	public function actionDashboard()
	{
		$this->layout = 'lay-admin';
		if(isset(Yii::$app->user->identity->id) && !empty(Yii::$app->user->identity->id))
		{
			$id	=	Yii::$app->user->identity->id;
		}
		$objGetContributorId	=	Contributors::find()->where(['user_id' => $id])->one();
		if(!empty($objGetContributorId))
 		{
			$ContributorId	=	$objGetContributorId->id;
		}
		else
		{
			return $this->redirect(['site/login']);
		}

        $searchModel = new Surveyssearch();
		$arrQuarterList	=	$searchModel->getQuartersList();

		/*-----added this two lines to send parameter to query -----*/

		$queryParams= Yii::$app->request->getQueryParams();
		$queryParams1= Yii::$app->request->getQueryParams();
		if(!empty($ContributorId))
		{
			$queryParams["Surveyssearch"]["contributor_id"] = $ContributorId ;
			$queryParams["Surveyssearch"]["completed"] = '1' ;
		}
		if(!empty($ContributorId))
		{
			$queryParams1["Surveyssearch"]["contributor_id"] = $ContributorId ;
			$queryParams1["Surveyssearch"]["completed"] = '0' ;
		}

		/*-----added this two lines to send parameter to query -----*/
		 $searchQuarter = array_values($arrQuarterList)[0];
       $CompleteddataProvider = $searchModel->search($queryParams, '');
	   $UncompleteddataProvider = $searchModel->search($queryParams1, '');
	   $CompleteddataProvider->pagination->defaultPageSize=50;
	   $UncompleteddataProvider->pagination->defaultPageSize=50;

        return $this->render('dashboard', [
            'dataProvider' => $CompleteddataProvider,
			'dataProvider1'=> $UncompleteddataProvider,
        ]);

	}

    /**
     * Displays a single Surveys model.
     * @param integer $id
     * @return mixed
     */
   /* public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Surveys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   /* public function actionCreate()
    {
        $model = new Surveys();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing Surveys model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing Surveys model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Surveys model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Surveys the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Surveys::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
