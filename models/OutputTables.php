<?php

namespace app\models;

use Yii;
use app\models\OutputTableColumnHeadings;
/**
 * This is the model class for table "output_tables".
 *
 * @property integer $id
 * @property string $heading
 * @property string $sub_heading
 * @property string $output_column
 * @property integer $survey_template_question_id
 * @property integer $first_field_id
 * @property integer $last_field_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $node_heading
 * @property integer $contributor_code
 * @property string $difference_presentation
 * @property string $parent_node_visibility
 * @property double $sd
 * @property integer $a_b_r
 * @property integer $show_parent_average
 */
class OutputTables extends \yii\db\ActiveRecord
{
    public $filters;
    public $return_column;
    public $hide_column;
    public $difference_column;
    public $value;
    public $column_heading ;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'output_tables';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_template_question_id', 'first_field_id', 'last_field_id', 'contributor_code', 'a_b_r', 'show_parent_average'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['sd'], 'number'],
            [['heading', 'sub_heading', 'output_column', 'node_heading', 'difference_presentation', 'parent_node_visibility'], 'string', 'max' => 255],
            [['heading', 'sub_heading'], 'required']
        ];
    }

    ## Defining relation b/w output table & field_filters
    public function getFieldFilters()
    {
        return $this->hasMany(FieldFilters::className(), ['output_table_id' => 'id']);
    }

    ## Defining relation b/w output table & output heading table
    public function getOutputColumnHeadings()
    {
        return $this->hasMany(OutputTableColumnHeadings::className(), ['output_table_id' => 'id']);
    }
    
    ## Defining relation b/w output table & output heading table
    public function getOutputColumnExclusions()
    {
        return $this->hasMany(OutputTableColumnExclusions::className(), ['output_table_id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'heading' => 'Heading',
            'sub_heading' => 'Sub Heading',
            'output_column' => 'Output Column',
            'survey_template_question_id' => 'Survey Template Question ID',
            'first_field_id' => 'First Field ID',
            'last_field_id' => 'Last Field ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'node_heading' => 'Node Heading',
            'contributor_code' => 'Contributor Code',
            'difference_presentation' => 'Difference Presentation',
            'parent_node_visibility' => 'Parent Node Visibility',
            'sd' => 'Sd',
            'a_b_r' => 'A B R',
            'show_parent_average' => 'Show Parent Average',
        ];
    }

   ## Static array for dropdown values while creating output table
    public function fieldFilters()
    {
        $arrFields = [
            'Mean,SD,n' => 'Mean,SD,n',
            'Mean,SD' => 'Mean,SD',
            'Mean' => 'Mean',
            'SD' => 'SD',
            'n' => 'n',
            'Current Q - Last Q' => 'Current Q - Last Q',
            'Highest Value' => 'Highest Value',
            //'Matched Pair' => 'Matched Pair',
            'Gross Income Yields' => 'Gross Income Yields'
         ];

         return $arrFields;
    }

    ## Static array values for additional column
    public function additionalColumns($arrDifference)
    {
        $arrAdditional = [
              'No Column'  => 'No Column',
              'n' => 'n',
              //'Difference' => 'Difference'
        ];
        if(!empty($arrDifference)) {
            $arrAdditional = array_merge($arrAdditional,[
                'Difference' => 'Difference'
            ]);
        }
        //print_r($arrAdditional);exit;
        return $arrAdditional;
    }

    ## Get first & last field id
    public function getFieldId($output_table_id, $data_id)
    {
        $strHeading = OutputTableColumnHeadings::find()
        ->select('heading')
        ->where(['output_table_id' => $output_table_id, 'data_field_template_id' =>$data_id])
        ->asArray()
        ->one();
       return !empty($strHeading) ? $strHeading['heading'] : '';
    } 
}
