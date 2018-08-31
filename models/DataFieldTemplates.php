<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_field_templates".
 *
 * @property integer $id
 * @property string $heading
 * @property string $field_type
 * @property integer $survey_template_question_id
 * @property integer $order
 * @property string $options
 */
class DataFieldTemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_field_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_template_question_id', 'order'], 'integer'],
            [['options'], 'string'],
            [['heading', 'field_type'], 'string', 'max' => 255],
            [['heading', 'field_type'], 'required'],
            ['options', 'required', 'when' => function($model) {
                return $model->field_type == 'selection';
                 },
                 'whenClient' => "function (attribute, value) {
        return $('#datafieldtemplates-field_type').val() == 'selection';
    }"],
          // ['options', 'checkBlank']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'heading' => 'Heading',
            'field_type' => 'Field Type',
            'survey_template_question_id' => 'Survey Template Question ID',
            'order' => 'Order',
            'options' => 'Options',
        ];
    }
    ## Define Relation between Data template field & Exclusion
    public function getExclusions()
    {
        return $this->hasMany(NodeFieldExclusions::ClassName(), ['data_field_template_id'=>'id'  ]);
    }
    
    ## Static array for field types
    public function getfieldTypes()
    {
            $arrFieldTypes = [
                    'Rand value'  => 'Rand value',
                    'percentage'  => 'percentage',
                    'integer'     => 'integer',
                    'selection'   => 'selection'
            ];

            return $arrFieldTypes;
    }

    ## Get max order of each question column
    public function getOrder($questId)
    {
        $maxOrder = DataFieldTemplates::find()
                    ->select('max(`order`)maxPostion')
                    ->where(['survey_template_question_id' => $questId])
                    ->asArray()
                    ->one();
        if(!empty($maxOrder)) {
            $intOrder = $maxOrder['maxPostion'] +1;
        } else {
            $intOrder = 1;
        }
        return $intOrder;
    }

    ## Function to Change sequence of nodes
    public static function setChangeSequence($arrPositions, $intCount)
    {
        $connection = Yii::$app->db;
        if (is_array($arrPositions)) {
            foreach ($arrPositions as $posId) {
                $connection ->createCommand()
                    ->update('data_field_templates', ['order' => $intCount ], 'id ='.$posId)
                    ->execute();
                $intCount++;
            }
            return json_encode(['status' => 'success', 'message' => POSITION_CHANGED]);
        } else {
            return json_encode(['status' => 'error', 'message' => POSITION_CHANGE_ERR]);
        }
    }
}
