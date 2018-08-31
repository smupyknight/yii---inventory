<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use yii\console\Controller;
use app\models\TempNotificationMail;
use app\models\Contributors;
use app\models\SurveyTemplates;
use app\models\SurveyQuarters;
use app\models\SurveyTemplateQuestions;
use app\models\EmailTemplates;
use yii\web\Request;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use app\models\TempDistribution;
use app\models\Surveys;
use yii\helpers\Html;
use yii\helpers\Url;

//use app\components\CustomComponent;

use yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
    /**
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $model = new TempNotificationMail();
    		$contributorArray=$model->getContributorList();
    		if(!empty($contributorArray)) {
    			for($i=0 ;$i<count($contributorArray); $i++) {
    				$contributor = new Contributors();
    				$ContributorDetail = $contributor->getContributorDetails($contributorArray[$i]['contributor_id']);


            if($ContributorDetail['disabled']!= 1){
              $connection = Yii::$app->db;
              $connection->createCommand()->delete('temp_notification_mail', ['id' => $contributorArray[$i]['id']])->execute();
              continue;
            }
    				$attremail = $ContributorDetail->rodeusers->getAttributes(array('email'));
            $to = $attremail["email"];
            //$to = "rastislav@wallit.eu";
    				$from = Yii::$app->params['adminEmail'];
    				$surveyDetail = SurveyTemplates::find()->where(['id'=>$contributorArray[$i]['survey_id']])->one();
    				$params = [];
    				if(!empty($surveyDetail)) {
      				$params = [
      					'name' => $ContributorDetail['firstname'].' '.$ContributorDetail['lastname'],
      					'survey' => $surveyDetail['name'],
      					'deadline' => date('d M,Y', strtotime($surveyDetail['deadline']))
      				];
    				}
        		$boolStatus = $model->sendEmail($from, $to, 'Notification Email' , 'notification_email', $params);
        		if($boolStatus) {
        			## Delete record once email is sent
        			//$x=$model->find($contributorArray[$i]['id']);
              $connection = Yii::$app->db;
              $connection->createCommand()->delete('temp_notification_mail', ['id' => $contributorArray[$i]['id']])->execute();
        			}
    			}
    		}
    }

    /**
     * @param string $message the message to be echoed.
     */
    public function actionMoveSurveys()
    {
        $connection = Yii::$app->db;
        $data = SurveyQuarters::find()
            ->where(['=', 'closed','1'])
            ->andWhere(['=','distributed','1'])
            ->andWhere(['<>', 'deadline' ,''])
            ->all();
        $data = ArrayHelper::toArray($data);
        $k = 0;
        foreach($data as $surveyQuarter){
            $nQuarter = SurveyQuarters::getNextQuarter($surveyQuarter['quarter']);
            $nextQuarter = SurveyQuarters::find()
            ->where(['=', 'survey_template_id', $surveyQuarter['survey_template_id']])
            ->andWhere(['=', 'quarter', $nQuarter])
            ->one();
            $nextQuarter = ArrayHelper::toArray($nextQuarter);



            if(!is_array($nextQuarter) || array_key_exists('0', $nextQuarter)) {
                $k++;
                $insertRow = [
                    'survey_template_id' => $surveyQuarter['survey_template_id'],
                    'distributed' => '0',
                    'quarter' => $nQuarter,
                    'distributable' => '1'
                ];
                //print_r($insertRow);

                $connection ->createCommand()
                    ->insert('survey_quarters', $insertRow)
                    ->execute();
                //print_r($nextQuarter);

            }
        }
        //print_r($k);
    }

    public function actionFillSurveys($lquart,$nquart)
    {
      $sql = "
        insert into surveys(survey_quarter_id,contributor_id,created_at,updated_at,completed,distributed,deleted)
        select 
        sq1.id as survey_quarter_id,
        s.contributor_id as contributor_id,
        NOW() as created_at,
        NOW() as updated_at,
        '0' as completed,
        '0' as distributed,
        '0' as deleted  
        from surveys s
        inner join survey_quarters sq on s.survey_quarter_id = sq.id
        left join survey_quarters sq1 on sq.survey_template_id = sq1.survey_template_id and sq1.`quarter` like ':nquart'
        where sq.`quarter` like ':lquart' 
        and sq.distributable = '1'
        GROUP BY sq.survey_template_id
      ";
      $connection=Yii::app()->db;
      $command=$connection->createCommand($sql);
      $command->bindParam(":lquart",$lquart,PDO::PARAM_STR);
      $command->bindParam(":nquart",$nquart,PDO::PARAM_STR);
      $command->execute();
    }

    public function actionPushSurveys($lquart,$nquart)
    {
      $sql = "
        insert into surveys(survey_quarter_id,contributor_id,created_at,updated_at,completed,distributed,deleted)
        select 
        sq1.id as survey_quarter_id,
        s.contributor_id as contributor_id,
        NOW() as created_at,
        NOW() as updated_at,
        '0' as completed,
        '1' as distributed,
        '0' as deleted  
        from surveys s
        inner join survey_quarters sq on s.survey_quarter_id = sq.id
        left join survey_quarters sq1 on sq.survey_template_id = sq1.survey_template_id and sq1.`quarter` like ':nquart'
        where sq.`quarter` like ':lquart' 
        and sq.distributable = '1'
      ";
      $connection=Yii::app()->db;
      $command=$connection->createCommand($sql);
      $command->bindParam(":lquart",$lquart,PDO::PARAM_STR);
      $command->bindParam(":nquart",$nquart,PDO::PARAM_STR);
      $command->execute();
    }

    ## Check if a survey is distributable or not
    ## Check if each question ahs data templates,& survey has 1 contributor atleast
    public function actionIsDistributable()
    {
    	$arrSurveys = $arrDistributable = $arrNonDistributable = [];
    	$model = SurveyQuarters::find()
    			->select('*')
    			->where(['<>', 'closed','1'])
				->andWhere(['<>','distributed','1'])
				->andWhere(['<>', 'deadline' ,''])
				->andWhere('deadline IS NOT NULL')
				// ->andWhere(['>=','deadline', date('Y-m-d H:i:s')])//deadline is not yet passed
				->all();
        //var_dump($model); die;
    	 if(!empty($model)) { //print_r($model); die;
    	 	$arrSurveys = yii\helpers\ArrayHelper::toArray($model);
    	 	## check if the data fields  are assigned to all he question of that survey
    	 	if(!empty($arrSurveys)) { //print_r($arrSurveys); die;
    	 		$flag = 0;// means survey can be distributed
    	 		foreach($arrSurveys as $arr) {
    	 			## Fetch all the questions of each survey
    	 			$arrQuestionIDs = [];
    	 			$questModel = SurveyTemplateQuestions :: find()
    	 			->select('id')
    	 			->where(['survey_template_id' => $arr['survey_template_id']])
    	 			->all();
    	 			if(!empty($questModel)) {
  						$questModel = ArrayHelper::toArray($questModel);
  						$arrQuestionIDs =  ArrayHelper::getColumn($questModel,'id');//print_r($arrQuestionIDs);
  						## Fetch data template fields of each question
  						## If 1 question out of 2 has no template fields ,it can not be distributed.
  						$query = new Query;
  						$query  ->select(['count(*),survey_template_question_id'])
  						    ->from('data_field_templates')
  						    ->where(['IN','survey_template_question_id', $arrQuestionIDs])
  						    ->groupBy('data_field_templates.survey_template_question_id');
  						//var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
  						$command = $query->createCommand();
  						$data = $command->queryAll();//echo count($data);echo count($arrQuestionIDs);
  						if(count($data) != count($arrQuestionIDs)) {  echo $arr['survey_template_id'];
  							$flag = 1;
  						}else {//echo count($data);echo count($arrQuestionIDs);
  							## Now that survey questions have field templates,
  							## check whether survey has contributors or not.
  							$arrLocationNodes = json_decode(SurveyTemplateQuestions::getQuestionNodes($arr['survey_template_id']));
  							if(!empty($arrLocationNodes->nodesArr) && !empty($arrLocationNodes->propertyArr) && !empty($arrLocationNodes->contributorArr)) {
  									$objQuery = (new \yii\db\Query())
                  							->select(['count(*) as tot_contributor'])
                  							->from('contributors')
  				                      ->innerJoin('contributor_nodes', 'contributor_nodes.contributor_id = contributors.id' )
  		      					          ->where(['IN', 'location_node_id', $arrLocationNodes->nodesArr])
  	           				          ->andWhere(['IN', 'property_type_id', $arrLocationNodes->propertyArr]);
             	      if($arrLocationNodes->contributorArr[0] != "Both") {
  	           		 		$objQuery->andWhere(['contributor_type' => $arrLocationNodes->contributorArr[0]]);
  	           		  }

  		           		$objCommand = $objQuery->createCommand();
  		           		//var_dump($objQuery->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
  									$arrData = $objCommand->queryAll();

  									## IF there is atleast 1 contributor , survey can be distributed
  					        		if(!empty($arrData) && $arrData[0]['tot_contributor']) {
  					        			$flag = 0;
  					        		} else {
  					        			$flag = 1;
  					        		}
  							} else {
  								$flag = 1;
  							}
  						}
					} else {
						$flag = 1;
					}
						## IF flag is 1 ,it cant be distributed, else distributable
					if($flag == 1) { //echo $arr['survey_template_id'];
						$arrNonDistributable [] = $arr['survey_template_id'];
					} else {
						$arrDistributable [] = $arr['survey_template_id'];
					}


				}

  				//echo 'cant :';print_r($arrNonDistributable);
  				//echo 'can :';print_r($arrDistributable);
  				## Based on these arrays ,set distributable as 1 & 0 accordingly.
  				if(!empty($arrDistributable)) {
  					SurveyQuarters::updateAll(['distributable' => 1, 'updated_at' => new Expression('NOW()')],['IN', 'survey_template_id', $arrDistributable]);
  				}
  				if(!empty($arrNonDistributable)) {
  					SurveyQuarters::updateAll(['distributable' => 0, 'updated_at' => new Expression('NOW()')],['IN', 'survey_template_id', $arrNonDistributable]);
  				}
    	 	}
    	 }

    }

    ## Distribute Surveys
    public function actionDistribute()
    {
    	$strMessage = '';
    	$strSubject = 'Rode Surveys are ready for you to contribute!';
    	$model = TempDistribution::find()->limit(1000)->all();
    	if(!empty($model)) {
        $contributorArray = $arrDelete = $primaryKeyArray = $insertRow = [];
    		$contributorArray = ArrayHelper::getColumn($model , 'contributor_id');
    		$primaryKeyArray = ArrayHelper::getColumn($model , 'id');
    		$surveyArray = ArrayHelper::getColumn($model , 'survey_id');
    		$quarterArray = ArrayHelper::getColumn($model , 'quarter');
    		$connection = Yii::$app->db;
    		$strEmailTemplate =  EmailTemplates::find()
    	->where(['id' => '1'])
    	->asArray()
    	->one();
    	$strMessage1 = $strEmailTemplate['body'];
    	$strSubject = $strEmailTemplate['subject'];

    		//$contributorArray = array_unique($contributorArray);
    		//print_r($contributorArray);print_r($primaryKeyArray);exit;
        print("Print contributors array:\n");
        print_r($contributorArray);
        print("\n\n");
    		if(!empty($contributorArray)) {
          print("Start cycling\n");
    			for($i=0; $i<count($contributorArray); $i++) {
            print("Cycle ".($i+1)."\n");
            $strMessage = $strMessage1;
    				$contributor = new Contributors();
    				$ContributorDetail = $contributor->getContributorDetails($contributorArray[$i]);
            if($ContributorDetail['disabled']==2) {
              ## Delete deactivated users
              $arrDelete[] = $primaryKeyArray[$i];
              continue;
            }
            $attremail = $ContributorDetail->rodeusers->getAttributes(array('email'));
    				//var_dump($attremail["email"]);continue;
    				$to = $attremail["email"];
            print("email: ".$to."\n");
    				//$to = 'rastislav@wallit.eu';
    				$from = Yii::$app->params['adminEmail'];
    				$surveyDetail = SurveyTemplates::find()
    				->select(['survey_templates.*','deadline','survey_quarters.id as quarter_id'])
    				->where(['survey_templates.id'=>$surveyArray[$i]])
    				->andWhere(['quarter'=>$quarterArray[$i]])
    				->joinWith('surveyQuarter')
    				->one();//print_r($surveyDetail);
    				//var_dump($surveyDetail->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
    				$params = [];
    				if(!empty($surveyDetail)) {
      					//echo Url::to('contributor/questions/index/4665');exit;
      				$strMessage = str_replace('##contributor_name##', $ContributorDetail['firstname'].' '.$ContributorDetail['lastname'], $strMessage);
      				$strMessage = str_replace('##survey_name##', $surveyDetail['name'], $strMessage);
      				$strMessage = str_replace('##survey_deadline##', date('d M,Y', strtotime($surveyDetail['deadline'])), $strMessage);
      				$strMessage = str_replace('##survey_link##', 'http://rodeonlinesurveys.co.za/contributor/questions/index/'.$surveyDetail['quarter_id'], $strMessage);
      				$params = [
      					'name' => $ContributorDetail['firstname'].' '.$ContributorDetail['lastname'],
      					'survey' => $surveyDetail['name'],
      					'deadline' => date('d M,Y', strtotime($surveyDetail['deadline'])),
      					'message' => $strMessage
      				];
            }
    		    print("Preparing to send\n");
            $boolStatus = TempNotificationMail::sendEmail($from, $to, $strSubject , 'distribution_email', $params);
        		if($boolStatus) {
              print("Sent\n");
        			## Delete record once email is sent
        			$arrDelete[] = $primaryKeyArray[$i];

        			$arrContributors[]  = $contributorArray[$i];
        			$arrQuarter[] = $surveyDetail['quarter_id'];


        			## insert data in surveys table
                    $sql = $connection->createCommand()->insert('surveys', [
                            'survey_quarter_id' => $surveyDetail['quarter_id'],
        					'contributor_id' => $contributorArray[$i],
        					'created_at' => new Expression('NOW()'),
        					'updated_at' => new Expression('NOW()'),
        					'distributed' => 1
                                    ])->execute();
                    $lastInsertID = $connection->getLastInsertID();
                    $intVersion =1;
                    ## Create row for auditing purpose
                    $addRow = "--- \ncontributor_id: ".$contributorArray[$i]."\ndistributed: 1 \ndeleted: \nnsurvey_quarter_id: ".$surveyDetail['quarter_id']."\ncompleted: \n";

        			## array for inserting in audit table
                    $insertRow[] = [
        					    	'auditable_type' => 'Survey',
        					    	'created_at' => new Expression('NOW()'),
        					    	'user_type' => 'User',
        					    	'action' => 'create',
        					    	'version' => $intVersion,
        					    	'username' => '',
        					    	'auditable_id' => $lastInsertID,
        					    	'user_id' => '1',
        					    	'changes' => $addRow
        						];

        			## array for inserting in survey_quarter_contributors
        			$insertData[] = [
        				'survey_quarter_id' => $surveyDetail['quarter_id'],
        				'contributor_id' => $contributorArray[$i]
        			];


        		}## if end
    			}## forloop end
    		}## main if end

      		## Delete all record in 1 query
      		if(!empty($arrDelete)) {
            print("Deleting the array of emails:\n");
            print_r($arrDelete);
            print("\n\n");
      			//$arrContributors = array_unique($arrContributors);
      			
      			$boolStatus = TempDistribution::deleteAll(['IN','id', $arrDelete]);

      			if($boolStatus && $arrQuarter) {
      			## insert into Audit table
            $arrQuarter = array_unique($arrQuarter);
      			$boolStatus=$connection ->createCommand()
       			->batchInsert('audits',['auditable_type', 'created_at','user_type','action','version','username','auditable_id','user_id','changes'], $insertRow)
      			->execute();

      			## Insert into survey_quarter_contributors
      			$connection ->createCommand()
      			->batchInsert('survey_quarter_contributors',['survey_quarter_id','contributor_id'],$insertData)
      			->execute();
      			}
      		}## end of delete if

      }## end of model
    
    }

    public function actionDistributeTest()
    {
      $strMessage = '';
      $strSubject = 'Rode Surveys are ready for you to contribute!';
      $model = TempDistribution::find()->limit(100)->all();
      if(!empty($model)) {
        $contributorArray = $arrDelete = $primaryKeyArray = $insertRow = [];
        $contributorArray = ArrayHelper::getColumn($model , 'contributor_id');
        $primaryKeyArray = ArrayHelper::getColumn($model , 'id');
        $surveyArray = ArrayHelper::getColumn($model , 'survey_id');
        $quarterArray = ArrayHelper::getColumn($model , 'quarter');
        $connection = Yii::$app->db;
        $strEmailTemplate =  EmailTemplates::find()
      ->where(['id' => '1'])
      ->asArray()
      ->one();
      $strMessage1 = $strEmailTemplate['body'];
      $strSubject = $strEmailTemplate['subject'];

        //$contributorArray = array_unique($contributorArray);
        print_r($contributorArray); print_r($primaryKeyArray);
        if(!empty($contributorArray)) { print("*prepare*");
          for($i=0; $i<count($contributorArray); $i++) { print("-loop-"); var_dump(count($contributorArray));
            $strMessage = $strMessage1;
            $contributor = new Contributors();
            $ContributorDetail = $contributor->getContributorDetails($contributorArray[$i]); var_dump($ContributorDetail['disabled']);
            if($ContributorDetail['disabled']==2) {
              continue; //deleting the user?
            }
            $attremail = $ContributorDetail->rodeusers->getAttributes(array('email'));
            var_dump($attremail["email"]); var_dump("mail"); var_dump(count($contributorArray));
            //$to = $attremail["email"];
            $to = 'rastislav@wallit.eu';
            $from = Yii::$app->params['adminEmail'];
            $surveyDetail = SurveyTemplates::find()
            ->select(['survey_templates.*','deadline','survey_quarters.id as quarter_id'])
            ->where(['survey_templates.id'=>$surveyArray[$i]])
            ->andWhere(['quarter'=>$quarterArray[$i]])
            ->joinWith('surveyQuarter')
            ->one();//print_r($surveyDetail);
            //var_dump($surveyDetail->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
            $params = [];
            if(!empty($surveyDetail)) {
                //echo Url::to('contributor/questions/index/4665');exit;
              $strMessage = str_replace('##contributor_name##', $ContributorDetail['firstname'].' '.$ContributorDetail['lastname'], $strMessage);
              $strMessage = str_replace('##survey_name##', $surveyDetail['name'], $strMessage);
              $strMessage = str_replace('##survey_deadline##', date('d M,Y', strtotime($surveyDetail['deadline'])), $strMessage);
              $strMessage = str_replace('##survey_link##', 'http://rodeonlinesurveys.co.za/contributor/questions/index/'.$surveyDetail['quarter_id'], $strMessage);
              $params = [
                'name' => $ContributorDetail['firstname'].' '.$ContributorDetail['lastname'],
                'survey' => $surveyDetail['name'],
                'deadline' => date('d M,Y', strtotime($surveyDetail['deadline'])),
                'message' => $strMessage
              ];
            }
        
            $boolStatus = true;//TempNotificationMail::sendEmail($from, $to, $strSubject , 'distribution_email', $params);
            if($boolStatus) {
              ## Delete record once email is sent
              $arrDelete[]  = $primaryKeyArray[$i];
              print_r($arrDelete);
              $arrContributors[]  = $contributorArray[$i];
              $arrQuarter[] = $surveyDetail['quarter_id'];


              ## insert data in surveys table
                    /*$sql = $connection->createCommand()->insert('surveys', [
                            'survey_quarter_id' => $surveyDetail['quarter_id'],
                  'contributor_id' => $contributorArray[$i],
                  'created_at' => new Expression('NOW()'),
                  'updated_at' => new Expression('NOW()'),
                  'distributed' => 1
                                    ])->execute();
                    $lastInsertID = $connection->getLastInsertID();
                    $intVersion =1;
                    ## Create row for auditing purpose
                    $addRow = "--- \ncontributor_id: ".$contributorArray[$i]."\ndistributed: 1 \ndeleted: \nnsurvey_quarter_id: ".$surveyDetail['quarter_id']."\ncompleted: \n";*/

              ## array for inserting in audit table
                    /*$insertRow[] = [
                        'auditable_type' => 'Survey',
                        'created_at' => new Expression('NOW()'),
                        'user_type' => 'User',
                        'action' => 'create',
                        'version' => $intVersion,
                        'username' => '',
                        'auditable_id' => $lastInsertID,
                        'user_id' => '1',
                        'changes' => $addRow
                    ];*/

              ## array for inserting in survey_quarter_contributors
              /*$insertData[] = [
                'survey_quarter_id' => $surveyDetail['quarter_id'],
                'contributor_id' => $contributorArray[$i]
              ];*/


            }## if end
          }## forloop end
        }## main if end

          ## Delete all record in 1 query
          if(!empty($arrDelete)) {
            //$arrContributors = array_unique($arrContributors);
            $arrQuarter = array_unique($arrQuarter);
            $boolStatus = TempDistribution::deleteAll(['IN','id', $arrDelete]);

            if($boolStatus) {
            ## insert into Audit table
            /*$boolStatus=$connection ->createCommand()
            ->batchInsert('audits',['auditable_type', 'created_at','user_type','action','version','username','auditable_id','user_id','changes'], $insertRow)
            ->execute();*/

            ## Insert into survey_quarter_contributors
            /*$connection ->createCommand()
            ->batchInsert('survey_quarter_contributors',['survey_quarter_id','contributor_id'],$insertData)
            ->execute();*/
            }
          }## end of delete if

      }## end of model
    
    }


}
