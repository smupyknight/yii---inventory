<?php


namespace app\controllers\contributor;

use Yii;
use app\models\contributor\Surveys;
use app\models\contributor\Contributors;
use app\models\contributor\Surveyquarters;
use app\models\contributor\Surveytemplatequestions;
use app\models\contributor\Surveytemplatequestionssearch;
use app\models\contributor\Surveytemplatequestionnodes;
use app\models\DataFieldTemplates;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;
use app\models\contributor\DataFields;
use app\models\Audits;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use app\models\contributor\SurveyComments;

/**
 * QuestionsController implements the CRUD actions for Surveytemplatequestions model.
 */
class QuestionsController extends Controller
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
                'only' => ['view','index','answer','markcomplete','addcomment'],
                'rules' => [
                   [
                        'actions' => ['view', 'index','answer','markcomplete','addcomment'],
                        'allow' => true,
						'roles' => [User::ROLE_CONTRIBUTOR],
                        //'roles' => ['@'],
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all Surveytemplatequestions models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $intSurveyQuarterId = $id;
        ## Check if survey belongs to logged in user only.
        $Loginid    =   Yii::$app->user->identity->id;
        $session = Yii::$app->session;
        $arrGetContributorId    =   Contributors::find()->select('id')
                    ->where(['user_id' => $Loginid])->asArray()->one();
        $intContributorId = isset($arrGetContributorId['id']) ? $arrGetContributorId['id'] : '';
        $objSurveyId   =   Surveys::find()->select(['id','completed'])
        ->where(['survey_quarter_id' => $intSurveyQuarterId,'contributor_id'=> $intContributorId])
        ->asArray()
        ->one();
        //print_r($objSurveyId);exit;
        ## IF not ,redirect with a error message
        if(empty($objSurveyId) || count($objSurveyId)<=0)
        {
            $session->setFlash('error',UNAUTHORISED_ACCESS);
           return $this->redirect(Yii::$app->request->referrer);
        }
        ## GEt Survey TEmplate ID based on quarter
        $objGetTemplateDetails  =   Surveyquarters::find()
        ->select(['survey_templates.*','survey_quarters.*'])
        ->joinWith('surveytemplates')
        ->where(['survey_quarters.id' => $intSurveyQuarterId])->one();

        $intSurveyTemplateId = '';
        if(!empty($objGetTemplateDetails))
        {
            $intSurveyTemplateId  =   $objGetTemplateDetails->survey_template_id;
        }
        $searchModel = new Surveytemplatequestionssearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $intSurveyTemplateId);
        //print_r($dataProvider->getModels());exit;

        ## Check if survey has been answered or not ,to show Complete Option.
        $ansCount = DataFields::find()->where(['survey_id' => $objSurveyId['id']])->count();

        ## If survey is completed , check if there is any comment added
        $commentModel = SurveyComments::find()
                        ->where(['survey_id' => $objSurveyId['id'] , 'contributor_id' => $intContributorId])
                        ->one();
        $session = Yii::$app->session;
        if(!empty($commentModel)) {
            if($commentModel->load(Yii::$app->request->post())) {
                $commentModel->updated_at = new Expression('NOW()');
                if ($commentModel->save()) {
                  $session->setFlash('success', COMMENT_UPDATE_SUCC);
                } else {
                   $session->setFlash('success', COMMENT_UPDATE_SUCC);
                }
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'SurveyQuarterId'=>$id,
            'ansCount' => $ansCount,
            'objGetTemplateDetails' => $objGetTemplateDetails,
            'surveyDetail' => $objSurveyId,
            'intContributorId' => $intContributorId,
            'commentModel' => $commentModel
        ]);
    }

    /**
     * Displays a single Surveytemplatequestions model.
     * @param integer $id
     * @return mixed
     */
    public function actionAnswer($id,$quarter_id)
    {
		/*----check for is survey qaurter id is belong to login contributor id-------*/
		$intSurveyQuarterId = $quarter_id;
        $intQuestionID = $id;
        $arroutputColumnExclusions = $arrNodes = $arrColumnHeadings = [];
        $previousQuarterID = '';
        $arrPreviousData = [];
        ## Check if survey belongs to logged in user only.
        $Loginid    =   Yii::$app->user->identity->id;
        $session = Yii::$app->session;
        $arrGetContributorId    =   Contributors::find()->select('id')
                    ->where(['user_id' => $Loginid])->asArray()->one();
        $intContributorId = isset($arrGetContributorId['id']) ? $arrGetContributorId['id'] : '';
        $objSurveyId   =   Surveys::find()->select(['id'])
        ->where(['survey_quarter_id' => $intSurveyQuarterId,'contributor_id'=> $intContributorId])
        ->asArray()
        ->one();
        //print_r($objSurveyId);exit;
        ## IF not ,redirect with a error message
        if(empty($objSurveyId) || count($objSurveyId)<=0)
        {
            $session->setFlash('error',UNAUTHORISED_ACCESS);
           return $this->redirect(Yii::$app->request->referrer);
        }
        /*----check for is survey qaurter id is belong to login contributor id-------*/

		/*----check for is survey template id is belong to login contributor id-------*/
		$objGetTemplateDetails	=	Surveyquarters::find()
        ->select(['survey_templates.*','survey_quarters.*'])
        ->joinWith(['surveytemplates'])//,'currentsurvey'//'contributor_id' => Yii::$app->user->identity->id
        ->where(['survey_quarters.id' => $intSurveyQuarterId])->one();
        //echo $intSurveyQuarterId;
        // print_r($objGetTemplateDetails);exit;
        $intTemplateId = '';
        if(!empty($objGetTemplateDetails))
        {
            $intTemplateId  =   $objGetTemplateDetails->survey_template_id;
        }

		$model	=	$this->findModel($id);


		if($model->survey_template_id==$intTemplateId)
		{
            $dataArr = [];
            $dataArr = DataFields::find()->where(['survey_id'=>$objSurveyId['id'], 'contributor_id' => $intContributorId])->asArray()->all();//print_r([$objSurveyId['id'],$intContributorId]);exit;
            //print_r($dataArr);exit;
            $dataModel = new DataFields();
            if($dataModel->load(Yii::$app->request->post())) {

                if($dataModel->validate()) {
               //echo '<pre/>';print_r(Yii::$app->request->post());exit;
                ## it means that the action is edit.so delete previous records & add again.

                $submittedValuesArr = $dataModel->value;
                $submittedTemplateIDArr = $dataModel->data_field_template_id;
                $submittedNodeIDArr = $dataModel->survey_template_question_node_id;
                //echo '<pre/>';print_r($submittedValuesArr);exit;
                
                $gonext = false;
                foreach ($submittedValuesArr as $arrkys) {
                    if ($arrkys!="") {
                        $gonext = true;
                    }
                }

                if($gonext) { //count($submittedValuesArr) >0
                    for($i=0;$i<count($submittedValuesArr);$i++) {

                        if(!empty($dataArr)) {
                            DataFields::DeleteAll([
                              'survey_id'=>$objSurveyId['id'],
                              'contributor_id' => $intContributorId,
                              'data_field_template_id' => $submittedTemplateIDArr[$i],
                              'survey_template_question_node_id' => $submittedNodeIDArr[$i],
                            ]);
                        }
                        if($submittedValuesArr[$i]!=""){

                          $insertRow[] = [
                             'survey_id' => $objSurveyId['id'],
                             'data_field_template_id' => $submittedTemplateIDArr[$i],
                             'survey_template_question_node_id' => $submittedNodeIDArr[$i],
                             'value' => $submittedValuesArr[$i],
                             'created_at' => new Expression('NOW()'),
                             'updated_at' => new Expression('NOW()'),
                             'quarter' => $objGetTemplateDetails->quarter,
                             'contributor_id' => $intContributorId,
                             'included' => '1' //bydefault included
                          ]; 
                        }

                    }
                    $connection = Yii::$app->db;
                    $boolStatus=$connection ->createCommand()
                        ->batchInsert('data_fields',[
                            'survey_id',
                            'data_field_template_id',
                            'survey_template_question_node_id',
                            'value',
                            'created_at',
                            'updated_at',
                            'quarter',
                            'contributor_id',
                            'included'
                            ], $insertRow)
                        ->execute();

                    if($boolStatus) {
                        $session->setFlash('success', ANS_SUB_SUCC);
                    } else {
                        $session->setFlash('success', ANS_SUB_ERR);
                    }
                    $arrNextId = $model->getNextRecord($model->created_at , $model->survey_template_id);
                    $nid = $arrNextId["id"];
                    if(!empty($nid)) {
                        return $this->redirect(['contributor/questions/answer/'.$arrNextId["id"].'/'.$intSurveyQuarterId]);
                    } else {
                        return $this->redirect(['index', 'id' => $intSurveyQuarterId]);
                    }
                } else {
                    //die('Please, fill the data.');
                    $session->setFlash('error','Please, fill the data.');
                }
                } else {
                    //print_r($dataModel->getErrors());exit;
                }

            }
            ## Fetch all the question nodes
            $arrparentNodes = SurveyTemplateQuestionNodes::find(['survey_template_question_nodes.*','position'])
                    ->where('survey_template_question_node_id IS NULL')
                    ->orWhere(['survey_template_question_node_id' => ''])
                    ->andWhere(['survey_template_question_id' => $model->id ])
                    ->joinWith('locations')
                    ->orderBy('position')
                    ->asArray()
                    ->all();
                     //ArrayHelper::multisort($arrNodes,['locations.position','survey_template_question_node_id'], [SORT_ASC,SORT_DESC]);
            if(!empty($arrparentNodes)) {
                foreach($arrparentNodes as $node) {
                    //echo '<pre/>';print_r($node);//exit;
                    $arrchildNodes = [];
                    $arrchildNodes = SurveyTemplateQuestionNodes::find(['survey_template_question_nodes.*','position'])
                    //->where('survey_template_question_node_id IS NOT NULL')
                    //->orWhere(['!=','survey_template_question_node_id',''])
                    ->where(['survey_template_question_id' => $model->id , 'survey_template_question_node_id' => $node['id']])
                    ->joinWith('locations')
                    ->orderBy('position')
                    ->asArray()
                    ->all();
                    if(!empty($arrchildNodes)) {
                        $node['hasChild'] = 1;
                    } else {
                        $node['hasChild'] = 0;
                    }
                    $arrNodes[] = $node;
                    if(!empty($arrchildNodes)) {
                   $arrNodes= array_merge($arrNodes , $arrchildNodes);
               }

                }
            }

            ## Fetch all data columns
            $arrColumnHeadings = DataFieldTemplates::find()->select(['data_field_templates.*','survey_template_question_node_id'])
                    ->where(['survey_template_question_id' => $model->id])
                    ->joinWith('exclusions')
                    ->orderBy('order')
                    ->asArray()
                    ->all();    //echo '<pre/>';print_r($arrColumnHeadings);
            $arroutputColumnExclusions =  ArrayHelper::map($arrColumnHeadings,'id', 'survey_template_question_node_id');

           $arroutputColumnExclusions =  array_unique($arroutputColumnExclusions);
           $dataArr =  ArrayHelper::map($dataArr,'data_field_template_id','value', 'survey_template_question_node_id');
         //echo '<pre/>';print_r($arrNodes);exit;

            ## Get Previous Question ID
            $arrPreviousId = $model->getPreviousRecord($model->created_at , $model->survey_template_id);
            $arrNextId = $model->getNextRecord($model->created_at , $model->survey_template_id);


            ## Get Previous quarter
            $surveyQuarter = $objGetTemplateDetails['quarter'];
            $previousQuarterID = $this->getpreviousQuarterID($objGetTemplateDetails['quarter'] ,  $objGetTemplateDetails['survey_template_id']);

            ## If previous quarter id is not empty, get answers for that quarter of logged in contributor
            if(!empty($previousQuarterID )) {
               $arrPreviousData = $this->getPreviousDataFields($intContributorId , $previousQuarterID);
               //echo '<pre/>';print_r($arrPreviousData);exit;
            }

            ## Get previous Quarter Mean
            $previousQuarterMean = $this->getPreviousQuarterMean($previousQuarterID);
//echo '<pre/>';print_r($previousQuarterMean);exit;
            return $this->render('view', [
        	    'model' => $model,
                'arrNodes' => $arrNodes,
                'arrColumnHeadings' => $arrColumnHeadings,
                'arroutputColumnExclusions' => $arroutputColumnExclusions,
                'dataModel' => $dataModel,
                'dataArr' => $dataArr,
                'intSurveyQuarterId' => $intSurveyQuarterId,
                'arrPreviousId' => $arrPreviousId,
                'arrNextId' => $arrNextId,
                'objGetTemplateDetails' => $objGetTemplateDetails,
                'arrPreviousData' => $arrPreviousData,
                'previousQuarterMean' => $previousQuarterMean
			]);
		}
		else
		{
			return $this->redirect(Yii::$app->request->referrer);
		}
		/*----check for is survey template id is belong to login contributor id-------*/
    }




    /**
     * Finds the Surveytemplatequestions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Surveytemplatequestions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Surveytemplatequestions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    ##---- Mark survey answer as completed ----##
    public function actionMarkcomplete()
    {
        $intSurveyId = Yii::$app->request->post('surveyId');
        $connection = Yii::$app->db;
        $boolStatus = $connection ->createCommand()
            ->update('surveys', ['completed' => 1 , 'updated_at' => new Expression('NOW()')], ['id' => $intSurveyId])
            ->execute();
        if($boolStatus) {
            $Loginid    =   Yii::$app->user->identity->id;
            $intVersion = 1;
            ## Once the survey is updated, you need to add it to audit table
            $maxVersionArr = Audits::find()->select('max(version) as max_version')
                                 ->where([
                                    'auditable_id' => $intSurveyId,
                                    'auditable_type' => 'Survey'
                                     ])
                                 ->asArray()
                                 ->One();
                if($maxVersionArr['max_version'] != '') {
                    $intVersion = $maxVersionArr['max_version'] + 1;
                }
                $insertRow = "--- \ncompleted:\n-\n- true";
                $connection->createCommand()->insert('audits', [
                    'auditable_type' => 'Survey',
                    'created_at' => new Expression('NOW()'),
                    'user_type' => 'User',
                    'action' => 'update',
                    'version' => $intVersion,
                    'username' => '',
                    'auditable_id' => $intSurveyId,
                    'user_id' => Yii::$app->user->identity->id,
                    'changes' => $insertRow
                ])
                ->execute();
                return json_encode(['result' => 'success']);

        } else {
               return json_encode(['result' => 'error', 'message' => SURVEY_COMPLETE_ERR]);
        }

    }

    ##----  Add comment after completing survey ----##
    public function actionAddcomment()
    {
        $model = new SurveyComments();
        $session = Yii::$app->session;
        $model->survey_id = Yii::$app->request->post('survey_id');
        $model->contributor_id = Yii::$app->request->post('contributor_id');
        $model->body = Yii::$app->request->post('body');
        $model->created_at = $model->updated_at = new Expression('NOW()');
        if($model->save()) {
            $session->setFlash('success',COMMENT_ADD_SUCC);
        } else {
            $session->setFlash('error',COMMENT_ADD_ERR);
        }
        return $this->redirect(Yii::$app->request->referrer);
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

    ##--- Get Previous Quarter Id of template ---##
    public function getpreviousQuarterID($surveyQuarter , $template_id) {
        $previousQuarter = $this->previousQuarter($surveyQuarter);
        $intQuarterId = '';
        $arrquarterId = Surveyquarters::find()
                        ->select(['id'])
                        ->where(['quarter' => $previousQuarter , 'survey_template_id' => $template_id ])
                        ->asArray()
                        ->one();
        if(!empty($arrquarterId)) {
            $intQuarterId = $arrquarterId['id'];
        }
        return $intQuarterId;
    }

    ##------  Get Previoud data fields --------##
    public function getPreviousDataFields($intContributorId , $previousQuarterID)
    {
        $arrDatafields = [];
        $arrDatafields = Surveys::find()->select(['data_fields.*'])
                 ->joinwith('datafields')
                 ->where([ 'survey_quarter_id' => $previousQuarterID , 'surveys.contributor_id' => $intContributorId])
                 ->asArray()
                 ->all();

        if(!empty($arrDatafields)) {
                $arrDatafields = ArrayHelper::map($arrDatafields , 'data_field_template_id','value', 'survey_template_question_node_id');
            }

        return $arrDatafields;
    }

    ## GEt all the surveys to find the mean
    public function getPreviousQuarterMean($previousQuarterID)
    {
        $arrDatafields = [];
        $arrDatafields = Surveys::find()->select(['avg(value) as average_val','survey_template_question_node_id','data_field_template_id','sum(value)','count(*)'])
                 ->joinwith('datafields')
                 ->where([ 'survey_quarter_id' => $previousQuarterID  ])
                 ->andWhere(['<>','value',''])
                 //->andWhere('value IS NOT NULL')
                 ->groupBy(['survey_template_question_node_id','data_field_template_id'])
                 //->asArray()
                 ->all();

        if(!empty($arrDatafields)) {
               $arrDatafields = ArrayHelper::map($arrDatafields , 'data_field_template_id','average_val', 'survey_template_question_node_id');
                //foreach()
            }


        return $arrDatafields;
    }
}
