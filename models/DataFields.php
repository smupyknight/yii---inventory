<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

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
    public $company_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_fields';
    }

    ## Define Relation between Quarters & Survey
    public function getSurveys()
    {
        return $this->hasOne(Surveys::ClassName(), ['id' => 'survey_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_id', 'data_field_template_id', 'survey_template_question_node_id', 'included', 'survey_template_node_category_id', 'contributor_id', 'interpolate'], 'integer'],
            [['created_at', 'updated_at','company_name'], 'safe'],
            [['exclude_reason'], 'string'],
            [['value', 'quarter'], 'string', 'max' => 255],
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
    public function getContributions($quarter_id ,$question_node_id ,$data_field_id, $currentQuarter)
    {
      $query = DataFields::find()
      ->innerJoinWith(['surveys'])
      ->where([
        'data_fields.survey_template_question_node_id' => $question_node_id,
        'data_fields.data_field_template_id' => $data_field_id,
        'data_fields.quarter' => $currentQuarter,
        'survey_quarter_id' => $quarter_id
      ])
      ->andWhere(['<>', 'value', ''])
      ->andWhere(['=', 'included', '1']);
      return $query->asArray()->all();
    }
    public function individualContributions($quarter_id ,$filter_id ,$question_node_id ,$data_field_id, $currentQuarter ,$previousQuarter)
    {
        $query = DataFields::find()->select([
        'data_fields.*','name as company_name','(CASE WHEN data_fields.quarter = "'.$currentQuarter.'" THEN value ELSE NULL END ) as value'
        ]);
        $query->addSelect(['(CASE WHEN data_fields.quarter = "'.$previousQuarter.'" THEN value ELSE NULL END ) as previous_value']);
        $query->innerJoinWith(['surveys','surveys.contributors','surveys.contributors.companies']);
        $query->where([
            'data_fields.survey_template_question_node_id' => $question_node_id,
            'data_fields.data_field_template_id' => $data_field_id,
            'survey_quarter_id' => $quarter_id
            ]);
        $query->andWhere(['<>','value','']);
        //$query->andWhere(['quarter' => $currentQuarter]);
        //$query->orWhere(['quarter' => $previousQuarter]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        //$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions


        /*$query->andFilterWhere(['like', 'survey_templates.name', $this->name])
            ->andFilterWhere(['like', 'publication', $this->publication])
            ->andFilterWhere(['like', 'contributor_type', $this->contributor_type])
            ->andFilterWhere(['like', 'survey_template_categories.name', $this->category])
            ->andFilterWhere(['=', 'closed', $this->status]);*/

        return $dataProvider;
    }
}
