<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "output_table_column_headings".
 *
 * @property integer $id
 * @property string $heading
 * @property integer $data_field_template_id
 * @property integer $output_table_id
 */
class OutputTableColumnHeadings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'output_table_column_headings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_field_template_id', 'output_table_id'], 'integer'],
            [['heading'], 'string', 'max' => 255],
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
            'data_field_template_id' => 'Data Field Template ID',
            'output_table_id' => 'Output Table ID',
        ];
    }

    ##---- Define Realtion b/w headings & data tempaltes----##
    public function getDataFields()
    {
        return $this->hasOne(DataFieldTemplates::className(), ['id' => 'data_field_template_id']);
    }
}
