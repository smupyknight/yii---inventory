<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "field_filters".
 *
 * @property integer $id
 * @property integer $output_table_id
 * @property integer $data_field_template_id
 * @property string $value
 * @property integer $return_column_id
 */
class FieldFilters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'field_filters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['output_table_id', 'data_field_template_id', 'return_column_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'output_table_id' => 'Output Table ID',
            'data_field_template_id' => 'Data Field Template ID',
            'value' => 'Value',
            'return_column_id' => 'Return Column ID',
        ];
    }

     ## Defining relation b/w output table & field_filters
    public function getOutputtable()
    {
        return $this->hasMany(OutputTables::className(), ['id' => 'output_table_id']);
    }
}
