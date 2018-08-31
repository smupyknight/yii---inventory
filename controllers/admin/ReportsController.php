<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : ReportsController.php
# Created on : 18th Dec 2015 by Bakhtawar Khan
# Update on : 18th Dec 2015 by Bakhtawar Khan
# Purpose : This page will perform CRUD on property types.
*/
namespace app\controllers\admin;

use Yii;
use app\models\User;
use yii\web\Controller;
use app\models\SurveySearch;
use app\models\SurveyTemplatesSearch;
use app\models\SurveyTemplateQuestions;
use app\models\SurveyTemplateQuestionNodes;
use app\models\SurveyTemplates;
use app\models\Contributors;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * PropertiesController implements the CRUD actions for PropertyTypes model.
 */
class ReportsController extends Controller
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
                'only' => ['contributors', 'surveys','inactivecontributors','excludedcontributors',
                'erraticcontributors','surveyquarter','individualcontribution'],
                'rules' => [
                   [
                        'actions' => ['contributors', 'surveys','inactivecontributors','excludedcontributors',
                        'erraticcontributors','surveyquarter','individualcontribution'],
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

   ##-------get erratic contributor -----------##
    public function actionErraticcontributors()
	{
		## Get all the contributors
        $query = Contributors::find()
        ->select(['contributors.id','user_id','users.disabled'])
        ->addSelect(["CONCAT(firstname, ' ', lastname) AS contributor_name"]);
        if(isset(Yii::$app->request->queryParams['SurveySearch']['contributor_name'])) {

            $strContributor = Yii::$app->request->queryParams['SurveySearch']['contributor_name'];
            $query->where('firstname LIKE "%' . trim($strContributor) . '%" ' .
            'OR lastname LIKE "%' . trim($strContributor) . '%"'.
            'OR CONCAT_WS (" ",firstname,lastname) LIKE "%' . trim($strContributor) . '%"');
        }
        $query->innerJoinWith('rodeusers');
        if(isset(Yii::$app->request->queryParams['sort']) && Yii::$app->request->queryParams['sort'] == '-contributor_name')
         {
            $query->orderBy([
            'firstname' => SORT_DESC,
            'lastname' => SORT_DESC
            ]);
        } else {
            $query->orderBy([
            'firstname' => SORT_ASC,
            'lastname' => SORT_ASC
            ]);
        }
        //->where(['disabled' => '1']) // active
        $contributorsList=$query->asArray()->all();//echo '<pre/>';print_r($contributorsList);exit;
        $searchmodel = new SurveySearch();
        $dataProvider = $searchmodel->getErraticcontributors(Yii::$app->request->queryParams, $contributorsList);
        echo $this->render('erratic-contributors', [
            'searchModel'  => $searchmodel,
            'dataProvider' => $dataProvider
            ]);

	}

    ##-------Get Inactive Contributors----------##
    public function actionInactivecontributors()
    {
        ## Get all the contributors
        $query = Contributors::find()
        ->select(['contributors.id','user_id','users.disabled'])
        ->addSelect(["CONCAT(firstname, ' ', lastname) AS contributor_name"]);
        if(isset(Yii::$app->request->queryParams['SurveySearch']['contributor_name'])) {

            $strContributor = Yii::$app->request->queryParams['SurveySearch']['contributor_name'];
            $query->where('firstname LIKE "%' . trim($strContributor) . '%" ' .
            'OR lastname LIKE "%' . trim($strContributor) . '%"'.
            'OR CONCAT_WS (" ",firstname,lastname) LIKE "%' . trim($strContributor) . '%"');
        }
        $query->innerJoinWith('rodeusers');
        if(isset(Yii::$app->request->queryParams['sort']) && Yii::$app->request->queryParams['sort'] == '-contributor_name')
         {
            $query->orderBy([
            'firstname' => SORT_DESC,
            'lastname' => SORT_DESC
            ]);
        } else {
            $query->orderBy([
            'firstname' => SORT_ASC,
            'lastname' => SORT_ASC
            ]);
        }
        $query->orderBy([
        'firstname' => SORT_ASC,
        'lastname' => SORT_ASC
        ]);
        //->where(['disabled' => '1']) // active
        $contributorsList=$query->asArray()->all();//echo '<pre/>';print_r($contributorsList);exit;

        $searchmodel = new SurveySearch();
        $dataProvider = $searchmodel->getInactivecontributors(Yii::$app->request->queryParams ,$contributorsList);

        echo $this->render('inactive-contributors',[
            'searchModel'  => $searchmodel,
            'dataProvider' => $dataProvider
            ]);
    }

    ##------Get Excluded Contributors------------##
    public function actionExcludedcontributors()
    {
        $searchmodel = new SurveySearch();
        $dataProvider = $searchmodel->getExcludedContributors(Yii::$app->request->queryParams);
        //$dataProvider->pagination->pagesize =20;
        return $this->render('excluded-contributors', [
            'searchModel' => $searchmodel,
            'dataProvider' => $dataProvider
            ]);
    }

    ##-------disable/enable user-------##
    public function actionTogglestatus($id, $status)
    {
            if($status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            $connection = Yii::$app->db;
          $boolAns = $connection ->createCommand()
                ->update('users', ['disabled' => $status],
                    'id ='.$id)
                ->execute();
         ## If updated
         $session = Yii::$app->session;
         if($boolAns) {
            $session->setFlash("success",STAT_UPDATE_SUCC);
         } else {
            $session->setFlash("error",STAT_UPDATE_ERR);
         }
        return $this->redirect(Yii::$app->request->referrer);
    }


    ##-------Survey Quarterly Report -----##
    public function actionSurveys()
    {
        $searchmodel = new SurveyTemplatesSearch();
        $dataProvider = $searchmodel->getQuarterlyContribution(Yii::$app->request->queryParams);
        //$dataProvider->pagination->pagesize =2;
        return $this->render('survey-contribution', [
            'searchModel' => $searchmodel,
            'dataProvider' => $dataProvider
            ]);
    }

    ##----SurveyQuarter Report---------##
    public function actionSurveyquarter($id, $quarter)
    {
       $questionId = [];
       $surveyDetails = SurveyTemplates::find()->where(['id' => $id])
                      ->asArray()
                      ->all();
       $questionIdsArr = SurveyTemplateQuestions::find()->select(['id'])
                      ->where(['survey_template_id' => $id])
                      ->asArray()
                      ->all();
        if(!empty($questionIdsArr))  {
            $questionId = ArrayHelper::getColumn($questionIdsArr , 'id');
        }//print_r($surveyDetails);exit;
        $searchModel = new SurveyTemplateQuestionNodes();
        $dataProvider = $searchModel->getNodewiseContribution(Yii::$app->request->queryParams ,$questionId ,$quarter);
        return $this->render('survey-quarterwise-contribution', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'surveyDetails' => $surveyDetails,
            'id' => $id,
            'quarter' => $quarter
            ]);

    }

    ## -----Show individual Contributions-----##
    public function actionIndividualcontribution($id ,$quarter , $node_id)
    {
        $surveyDetails = SurveyTemplates::find()->where(['id' => $id])
                      ->asArray()
                      ->all();
        $nodeDetail = SurveyTemplateQuestionNodes::find()
                     ->select(['name'])
                     ->where(['id' => $node_id])
                     ->asArray()
                     ->one();
        $searchModel = new SurveySearch();
        $dataProvider = $searchModel->getIndividualContributions(Yii::$app->request->queryParams , $node_id , $quarter);
        return $this->render('survey-individual-contribution', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'surveyDetails' => $surveyDetails,
            'id' => $id,
            'quarter' => $quarter,
            'node_id' => $node_id,
            'nodeDetail' => $nodeDetail
            ]);
    }

    ## -----Show survey answers-----##
    public function actionSurveyanswers($id ,$quarter , $node_id)
    {
        $surveyDetails = SurveyTemplates::find()->where(['id' => $id])
                      ->asArray()
                      ->all();
        $nodeDetail = SurveyTemplateQuestionNodes::find()
                     ->select(['name'])
                     ->where(['id' => $node_id])
                     ->asArray()
                     ->one();
        $searchModel = new SurveySearch();

        $dataProvider = $searchModel->getSurveyans($id ,$quarter , $node_id);

        return $this->render('survey-answers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'surveyDetails' => $surveyDetails,
            'id' => $id,
            'quarter' => $quarter,
            'node_id' => $node_id,
            'nodeDetail' => $nodeDetail
            ]);
    }

    ##-------Show publication report---------------##
    public function actionPublication()
    {
        $model = new SurveyTemplates();
        $arrPublication = $model->getPublicationList();
        $arrQuarter = $model->getQuartersList();
        $searchModel = new SurveySearch();
        $postedArray = Yii::$app->request->queryParams;
        if(!isset($postedArray['SurveySearch']['publication'])) {
            $postedArray['SurveySearch']['publication'] = 'Rode Report';
        }
        $postedArray['SurveySearch']['publication'] = str_replace('+', ' ', $postedArray['SurveySearch']['publication']);
        $searchModel->publication = $postedArray['SurveySearch']['publication'];
        $searchModel->quarter = isset($postedArray['SurveySearch']['quarter']) ? $postedArray['SurveySearch']['quarter'] : '';
        $dataProvider = $searchModel->getPublicationContributions($postedArray);
        return $this->render('publication-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arrPublication' => $arrPublication,
            'arrQuarter' => $arrQuarter,
            ]);


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
