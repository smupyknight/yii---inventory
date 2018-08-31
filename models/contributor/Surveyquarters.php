<?php

namespace app\models\contributor;

use Yii;

/**
 * This is the model class for table "survey_quarters".
 *
 * @property integer $id
 * @property integer $survey_template_id
 * @property string $distributed
 * @property string $quarter
 * @property string $closed
 * @property string $deadline
 * @property string $created_at
 * @property string $updated_at
 * @property integer $distributable
 */
class Surveyquarters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	
	public function getSurveytemplates()
	{
		return $this->hasOne(Surveytemplates::className(), ['id' => 'survey_template_id']);
	}
	
	public function getSurveytemplatequestions()
	{
		return $this->hasMany(Surveytemplatequestions::className(), ['survey_template_id' => 'survey_template_id']);
	}
	 
    public static function tableName()
    {
        return 'survey_quarters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_template_id', 'distributable'], 'integer'],
            [['distributed', 'closed'], 'string', 'max' => 1],
            [['quarter'], 'string', 'max' => 6],
            [['deadline', 'created_at', 'updated_at'], 'string', 'max' => 19],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_template_id' => 'Survey Template ID',
            'distributed' => 'Distributed',
            'quarter' => 'Quarter',
            'closed' => 'Closed',
            'deadline' => 'Deadline',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'distributable' => 'Distributable',
			'name'=>'Name',
        ];
    }
}
