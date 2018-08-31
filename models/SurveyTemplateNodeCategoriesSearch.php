<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SurveyTemplateNodeCategories;

/**
 * SurveyTemplateNodeCategoriesSearch represents the model behind the search form about `app\models\SurveyTemplateNodeCategories`.
 */
class SurveyTemplateNodeCategoriesSearch extends SurveyTemplateNodeCategories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'survey_template_id', 'active', 'property_name'], 'safe'],
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
    public function search($params)
    {
        $query = SurveyTemplateNodeCategories::find()
		->joinWith(['nodeProperty'])
		->select(['survey_template_node_categories.*', 'property_types.name as property_name']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
		$dataProvider->sort->attributes['property_name'] = [
			'asc' => [ 'property_types.name' => SORT_ASC ],
			'desc' => ['property_types.name' => SORT_DESC ]
		];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'property_types.name', $this->property_name])
            ->andFilterWhere(['like', 'survey_template_id', $this->survey_template_id])
            ->andFilterWhere(['like', 'active', $this->active]);

        return $dataProvider;
    }
}
