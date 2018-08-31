<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OutputTables;

/**
 * OutputTablesSearch represents the model behind the search form about `app\models\OutputTables`.
 */
class OutputTablesSearch extends OutputTables
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_template_question_id', 'first_field_id', 'last_field_id', 'contributor_code', 'a_b_r', 'show_parent_average'], 'integer'],
            [['heading', 'sub_heading', 'output_column', 'created_at', 'updated_at', 'node_heading', 'difference_presentation'], 'safe'],
            //[['sd'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $intQuestionId)
    {
        $query = OutputTables::find()
                ->where(['survey_template_question_id' => $intQuestionId]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'survey_template_question_id' => $this->survey_template_question_id,
            'first_field_id' => $this->first_field_id,
            'last_field_id' => $this->last_field_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contributor_code' => $this->contributor_code,
            'sd' => $this->sd,
            'a_b_r' => $this->a_b_r,
            'show_parent_average' => $this->show_parent_average,
        ]);

        $query->andFilterWhere(['like', 'heading', $this->heading])
            ->andFilterWhere(['like', 'sub_heading', $this->sub_heading])
            ->andFilterWhere(['=', 'output_column', $this->output_column])
            ->andFilterWhere(['like', 'node_heading', $this->node_heading])
            ->andFilterWhere(['like', 'difference_presentation', $this->difference_presentation])
            ->andFilterWhere(['like', 'parent_node_visibility', $this->parent_node_visibility]);

        return $dataProvider;
    }
}
