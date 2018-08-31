<?php

namespace app\models\contributor;

use Yii;

/**
 * This is the model class for table "data_fields".
 *
 * @property integer $id
 * @property integer $survey_id
 * @property integer $data_field_template_id
 * @property integer $survey_template_question_node_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property integer $included
 * @property string $quarter
 * @property string $exclude_reason
 * @property integer $survey_template_node_category_id
 * @property integer $contributor_id
 * @property integer $interpolate
 */
class DataFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['survey_id', 'data_field_template_id', 'survey_template_question_node_id', 'included', 'survey_template_node_category_id', 'contributor_id', 'interpolate'], 'integer'],
            //[['created_at', 'updated_at'], 'safe'],
            //[['exclude_reason'], 'string'],
           // [['value', 'quarter'], 'string', 'max' => 255],
           // ['value','required']
        [['data_field_template_id', 'survey_template_question_node_id','value'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_id' => 'Survey ID',
            'data_field_template_id' => 'Data Field Template ID',
            'survey_template_question_node_id' => 'Survey Template Question Node ID',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'included' => 'Included',
            'quarter' => 'Quarter',
            'exclude_reason' => 'Exclude Reason',
            'survey_template_node_category_id' => 'Survey Template Node Category ID',
            'contributor_id' => 'Contributor ID',
            'interpolate' => 'Interpolate',
        ];
    }
}
