<?php

namespace app\models\contributor;

use Yii;
use app\models\LocationNodes;

/**
 * This is the model class for table "survey_template_question_nodes".
 *
 * @property integer $id
 * @property string $name
 * @property integer $survey_template_question_id
 * @property integer $location_node_id
 * @property string $survey_template_question_node_id
 * @property integer $included
 * @property integer $capture_level
 */
class Surveytemplatequestionnodes extends \yii\db\ActiveRecord
{
    public $position;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_template_question_nodes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_template_question_id', 'location_node_id', 'included', 'capture_level'], 'integer'],
            [['name'], 'string', 'max' => 102],
            [['survey_template_question_node_id'], 'string', 'max' => 5],
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
}
