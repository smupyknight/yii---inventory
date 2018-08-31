<?php

namespace app\models;

use Yii;
use app\models\PropertyTypes;
use app\models\SurveyTemplates;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use app\models\SurveyTemplateQuestionNodes;

/**
 * This is the model class for table "survey_template_questions".
 *
 * @property integer $id
 * @property string $question
 * @property string $information
 * @property integer $survey_template_id
 * @property integer $property_type_id
 * @property string $old_type
 * @property string $created_at
 * @property string $use_categories
 */
class SurveyTemplateQuestions extends \yii\db\ActiveRecord
{
	public $propertyName;
    public $nodes;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_template_questions';
    }

    public function afterFind(){
       $this->oldAttributes = $this->attributes;
       return parent::afterFind();
    }

    ## Define Relation between Category & Survey
    public function getSurvey()
    {
        return $this->hasOne(SurveyTemplates::ClassName(), ['id' => 'survey_template_id']);
    }



    ## Define Relation between Category & Survey
    public function getSurveyNodes()
    {
        return $this->hasMany(SurveyTemplateQuestionNodes::ClassName(), ['survey_template_question_id' => 'id']);
    }

    ## Perform Audit action after save
    public function afterSave($insert, $changedAttributes)
    {
        $strChanges = $strAction ='';
        $attributesArr = [];
        $attArray = array("use_categories","property_type_id","information","old_type","survey_template_id","question");

        if(Yii::$app->controller->action->id == 'addquestion') {
            $newAttributes = (array) $this->attributes;
            unset($newAttributes['updated_at']);
            unset($newAttributes['created_at']);
            //print_r($newAttributes);exit;
           $strAction = 'create';
           ## Need to change order of array values to match with the old records
           foreach($attArray as $arr) {
                $attributesArr[$arr] =  $newAttributes[$arr];
           }
           //print_r($attributesArr);exit;
            if(!empty($attributesArr)) {
                foreach($attributesArr as $k=>$v) {
                    if($strChanges == "") {
                            $strChanges = "---\n";
                     }
                    if(in_array($k , ['information','question'])) {
                        $strChanges .=$k.": |- \n".$v."\n";
                    } else {
                         $strChanges .=$k.":".$v."\n";
                    }
                }
            }
           //echo $strChanges;exit;
        }
        if(Yii::$app->controller->action->id == 'updatequestion') {
            $strAction = 'update';
            $strChanges = '';//print_r($changedAttributes);
            ## Need to change order of array values to match with the old records
           foreach($attArray as $arr) {
               if (array_key_exists($arr, $changedAttributes)) {
                $attributesArr[$arr] =  $changedAttributes[$arr];
            }
           }//print_r($attributesArr);exit;
            foreach($attributesArr as $k=>$v) {
                if($this->$k != $v ) {
                    if($strChanges == "") {
                        $strChanges = "---\n";
                    }
                    if(in_array($k , ['information','question'])) {
                        $strChanges .=$k.":\n- |- ".$v."\n- |-".$this->$k."\n";
                        //$strChanges .=$k." :\n-".$v."\n-".$this->$k.PHP_EOL;
                    } else {
                        $strChanges .=$k.":\n-".$v."\n-".$this->$k."\n";
                    }
                }

            }//echo $strChanges;exit;
        }
        if($strChanges != "") {
                $intVersion = 1;
                $maxVersionArr = Audits::find()->select('max(version) as max_version')
                                         ->where([
                                            'auditable_id' => $this->id,
                                            'auditable_type' => 'SurveyTemplateQuestion'
                                             ])
                                         ->asArray()
                                         ->One();
                        if($maxVersionArr['max_version'] != '') {
                            $intVersion = $maxVersionArr['max_version'] + 1;
                        }
                        $connection = Yii::$app->db;
                        $connection->createCommand()->insert('audits', [
                            'auditable_type' => 'SurveyTemplateQuestion',
                            'created_at' => new Expression('NOW()'),
                            'user_type' => 'User',
                            'action' => $strAction,
                            'version' => $intVersion,
                            'username' => '',
                            'auditable_id' => $this->id,
                            'user_id' => Yii::$app->user->identity->id,
                            'changes' => $strChanges
                        ])
                        ->execute();
            }
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           [['id', 'survey_template_id', 'property_type_id'], 'integer'],
            [['question', 'information'], 'string'],
            [['old_type'], 'string', 'max' => 60],
            //[['use_categories'], 'string', 'max' => 10],
			[['property_type_id', 'question', 'information'], 'trim'],
			[['property_type_id','question'], 'required'],
			/*['question', 'required',  'when' => function($model) {
        		return $model->question == '<p><br></p>';
				},
				'whenClient' => "function (attribute, value) {
        return $('#question').val() == '<p><br></p>';
    }"]*/
	 ['question', 'validateEditor', 'when' => function ($model) {
        return $model->question == '<p><br></p>';
    }, 'whenClient' => "function (attribute, value) {
        return $('#question').val() == '<p><br></p>';
    }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'information' => 'Information',
            'survey_template_id' => 'Survey Template ID',
            'property_type_id' => 'Property Type',
            'old_type' => 'Old Type',
            'created_at' => 'Created At',
            'use_categories' => 'Use Categories',
        ];
    }

	##--- Define relation between question & property types----##
	public function getPropertyTypes()
	{
		return $this->hasOne(PropertyTypes:: className(), ['id' => 'property_type_id']);
	}

    ##--- Define relation between question & outputtables----##
    public function getOutputtables()
    {
        return $this->hasMany(OutputTables:: className(), ['survey_template_question_id' => 'id']);
    }

	##------Get Property List ------##
	public static function getPropertyList()
	{
		$propertyList = PropertyTypes::find()
						->select(['id','name'])
						->where(['status' => 'Active'])
						->orderBy(['name' => SORT_ASC])
						->asArray()
						->all();
		$propertyList = ArrayHelper::map($propertyList , 'id', 'name');
		return $propertyList;
	}
	## Custom validation for editor
	public function validateEditor($attribute, $params)
	{
		 $this->addError($attribute, QUESTION_BLANK_ERR);
	}

    ## Fetch questions of a survey
    public static function getQuestionNodes($id) {
        $nodesArr = $propertyArr = $contributorArr = [];
       /* $nodes = SurveyTemplateQuestions::find()
                ->select(['property_type_id','location_node_id as nodes'])
                 ->joinWith(['survey','surveyNodes'])
                 ->where(['survey_templates.id' => $id, 'capture_level' => '1' , 'included' => '1'])
                 ->distinct()
                // ->asArray()
                 ->all();*/
        $nodes = (new \yii\db\Query())
                ->select(['property_type_id','location_node_id as nodes','contributor_type'])
                ->from('survey_template_questions')
                ->leftJoin('survey_templates', 'survey_templates.id = survey_template_questions.survey_template_id' )
                ->leftJoin('survey_template_question_nodes', 'survey_template_question_nodes.survey_template_question_id = survey_template_questions.id' )
                ->where(['survey_templates.id' => $id, 'capture_level' => '1' , 'included' => '1'])
                ->distinct()
                ->all();
                 //var_dump($nodes->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);

        if(!empty($nodes)) {
            $nodesArr = ArrayHelper::getColumn($nodes, 'nodes');
            $propertyArr = ArrayHelper::getColumn($nodes, 'property_type_id');
            $contributor_type = ArrayHelper::getColumn($nodes, 'contributor_type');

            ## Remove duplicate elements
            $propertyArr = array_unique($propertyArr);
            $contributorArr = array_unique($contributor_type);
         }
         return json_encode([ 'nodesArr' => $nodesArr, 'propertyArr' => $propertyArr, 'contributorArr' => $contributorArr]);
        //echo '<pre/>';print_r($propertyArr );exit;
    }
}
