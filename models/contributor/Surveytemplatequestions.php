<?php

namespace app\models\contributor;

use Yii;

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
class Surveytemplatequestions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_template_questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'information'], 'string'],
            [['survey_template_id', 'property_type_id'], 'integer'],
            [['old_type'], 'string', 'max' => 60],
            [['created_at'], 'string', 'max' => 18],
            [['use_categories'], 'string', 'max' => 10],
            
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
            'property_type_id' => 'Property Type ID',
            'old_type' => 'Old Type',
            'created_at' => 'Created At',
            'use_categories' => 'Use Categories',
        ];
    }


    /*
    * function to get previous record
    */
    public function getPreviousRecord($dateCreated , $surveyId)
    {
        $sql = 'select id from survey_template_questions where 
                created_at = (select max(created_at) from survey_template_questions 
                where 
                created_at < \''.$dateCreated.'\' and survey_template_id = "'.$surveyId.'" )';
        $arrPreviousRec = self::findBySql($sql)->asArray()->one();
        return $arrPreviousRec;
    }
    
    /*
    * function to get next record
    */
    public function getNextRecord($dateCreated, $surveyId)
    {
        $sql = 'select id from survey_template_questions where 
                created_at = (select min(created_at) from survey_template_questions 
                where 
                created_at > \''.$dateCreated.'\' and survey_template_id = "'.$surveyId.'" )';
        $arrNextRec = self::findBySql($sql)->asArray()->one();
        return $arrNextRec;
    }
}
