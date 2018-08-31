<?php


namespace app\controllers\contributor;

use Yii;
use app\models\contributor\Surveys;
use app\models\contributor\Contributors;
use app\models\contributor\Surveyquarters;
use app\models\contributor\Surveyquarterssearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;

/**
 * SurveyquartersController implements the CRUD actions for Surveyquarters model.
 */
class SurveyquartersController extends Controller
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
     * Lists all Surveyquarters models.
     * @return mixed
     */
    public function actionIndex($id)
    {
		/*----check for is survey qaurter id is belong to login contributor id-------*/
		if(!empty($id))
		{
				if(isset(Yii::$app->user->identity->id) && !empty(Yii::$app->user->identity->id))
				{
					$Loginid	=	Yii::$app->user->identity->id;
				}
				$objGetContributorId	=	Contributors::find()->where(['user_id' => $Loginid])->one();
				if(!empty($objGetContributorId))
				{
					$ContributorId	=	$objGetContributorId->id;
				}
				else
				{
					return $this->redirect(['contributor/surveys/index']);
				}
				
				$objGetId	=	Surveys::find()->where(['survey_quarter_id' => $id,'contributor_id'=>$ContributorId])->count();
				if($objGetId<=0)
				{
					return $this->redirect(['contributor/surveys/index']);
				}	
		}
		/*----check for is survey qaurter id is belong to login contributor id-------*/
		
		
        $searchModel = new Surveyquarterssearch();
		$queryParams = Yii::$app->request->queryParams;
		if(!empty($id))
		{
			$objGetTemplateId = Surveyquarters::find()->where(['id' => $id])->one();
			if(!empty($objGetTemplateId))
			{
				$intSurveyTemplateId	=	$objGetTemplateId->survey_template_id;
			}
			$queryParams['Surveyquarterssearch']['id']	=	$id;
			$queryParams['Surveyquarterssearch']['survey_template_id']	=	$intSurveyTemplateId;
		}
        $dataProvider = $searchModel->search($queryParams);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'SurveyQuarterId'=>$id,
        ]);
    }

    /**
     * Displays a single Surveyquarters model.
     * @param integer $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Surveyquarters model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   /* public function actionCreate()
    {
        $model = new Surveyquarters();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing Surveyquarters model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionUpdate($id)
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
     * Deletes an existing Surveyquarters model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
*/
    /**
     * Finds the Surveyquarters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Surveyquarters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Surveyquarters::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
