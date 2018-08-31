<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "survey_template_question_nodes".
 *
 * @property integer $id
 * @property string $name
 * @property integer $survey_template_question_id
 * @property integer $location_node_id
 * @property integer $survey_template_question_node_id
 * @property integer $included
 * @property integer $capture_level
 */
class SurveyTemplateQuestionNodes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //protected static $s ='fdsfs';
    public static function tableName()
    {
        return 'survey_template_question_nodes';
    }

    public function afterSave($insert, $changedAttributes)
    {
        $strChanges = $strAction ='';
        $attributesArr =[];
        $attArray = array("name","capture_level","location_node_id","included","survey_template_question_id","survey_template_question_node_id");
        if(Yii::$app->controller->action->id == 'addquestion') {
            $newAttributes = (array) $this->attributes;
            $strAction = 'create';
            ## Need to change order of array values to match with the old records
           foreach($attArray as $arr) {
                $attributesArr[$arr] =  $newAttributes[$arr];
           }

            if(!empty($attributesArr)) {
                foreach($attributesArr as $k=>$v) {
                    if($strChanges == "") {
                            $strChanges = "---\n";
                     }
                    $strChanges .=$k.": ".$v."\n";
                }
            }
       }
       if(Yii::$app->controller->action->id == 'updatequestion') {
            $newAttributes = (array) $this->attributes;
            $strAction = 'update';
            ## Need to change order of array values to match with the old records
           foreach($attArray as $arr) {
                $attributesArr[$arr] =  $newAttributes[$arr];
           }

            if(!empty($attributesArr)) {
                foreach($attributesArr as $k=>$v) {
                    if($strChanges == "") {
                            $strChanges = "---\n";
                     }
                    $strChanges .=$k.": ".$v."\n";
                }
            }
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
                    'auditable_type' => 'SurveyTemplateQuestionNode',
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
            [['survey_template_question_id', 'location_node_id', 'survey_template_question_node_id', 'included', 'capture_level'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'survey_template_question_id' => 'Survey Template Question ID',
            'location_node_id' => 'Location Node ID',
            'survey_template_question_node_id' => 'Survey Template Question Node ID',
            'included' => 'Included',
            'capture_level' => 'Capture Level',
        ];
    }

     ##define relation between nodes & location nodes
    public function getLocations()
    {
        return $this->hasOne(LocationNodes::className(), ['id' => 'location_node_id']);
    }

    public function getNodewiseContribution($params , $questionIds , $quarter)
    {
        $query = (new \yii\db\Query())
                ->select(['survey_template_question_nodes.*','count(data_fields.id) as contribution'])
                ->where(['IN', 'survey_template_question_id',$questionIds])
                ->andWhere(['survey_template_question_nodes.included' => 1 , 'capture_level' => 1])
                ->from('survey_template_question_nodes');
        $query->leftJoin('data_fields', 'data_fields.survey_template_question_node_id = survey_template_question_nodes.id and data_fields.included=1 and quarter ="'.$quarter.'" and value !=""' );
        //$query->innerJoin('survey_template_question_nodes', 'survey_template_question_nodes.id = data_fields.survey_template_question_node_id' );
        //$query->innerJoin('survey_template_questions', 'survey_template_questions.id = survey_template_question_nodes.survey_template_question_id' );
        //$query->innerJoin('contributors', 'contributors.id = surveys.contributor_id' );
        //$query->innerJoin('survey_quarters', 'survey_quarters.id = surveys.survey_quarter_id' );
        //$query->innerJoin('survey_templates', 'survey_templates.id = survey_quarters.survey_template_id' );

        $query->groupBy('survey_template_question_nodes.id');

       /* $query->andFilterWhere([
            'id' => $this->id,
            'survey_quarter_id' => $this->survey_quarter_id,
            'contributor_id' => $this->contributor_id,
            'distributed' => $this->distributed,
            'deleted' => $this->deleted,
        ]);*/
        $this->load($params);
        //echo $this->contributor_name;exit;


        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => ['survey_template_question_id'],
                'defaultOrder' => [
                    'survey_template_question_id' => SORT_ASC, 
                    //'survey_templates.name' => SORT_ASC,
                    //'survey_quarters.quarter' => SORT_ASC,
                    //'survey_template_question_id' => SORT_ASC,
                    //'node' => SORT_ASC,

                    ]
                ],
            'pagination' => [
            'pageSize' => 100,
            ],
        ]);
        $data = $dataProvider->allModels;
        return $dataProvider;
    }
}
