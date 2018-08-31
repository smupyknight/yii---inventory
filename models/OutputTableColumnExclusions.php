<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "output_table_column_exclusions".
 *
 * @property integer $id
 * @property integer $data_field_template_id
 * @property integer $output_table_id
 * @property integer $excluded
 */
class OutputTableColumnExclusions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'output_table_column_exclusions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_field_template_id', 'output_table_id', 'excluded'], 'integer'],
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
            'output_table_id' => 'Output Table ID',
            'excluded' => 'Excluded',
        ];
    }
}
