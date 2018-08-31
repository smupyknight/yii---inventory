<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "node_field_exclusions".
 *
 * @property integer $id
 * @property integer $data_field_template_id
 * @property integer $survey_template_question_node_id
 */
class NodeFieldExclusions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node_field_exclusions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_field_template_id', 'survey_template_question_node_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_field_template_id' => 'Data Field Template ID',
            'survey_template_question_node_id' => 'Survey Template Question Node ID',
        ];
    }
}
