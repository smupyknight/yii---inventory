<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LocationNodes;

/**
 * SearchLocationNodes represents the model behind the search form about `app\models\LocationNodes`.
 */
class SearchLocationNodes extends LocationNodes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'property_type_id', 'position'], 'integer'],
            [['name', 'code', 'location_node_id', 'created_at', 'status'], 'safe'],
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
    public function search($params, $intPropId)
    {
        $query = LocationNodes::find()

				->where('location_node_id IS NULL')
				->orWhere(['location_node_id' => ''])
				->andWhere(['property_type_id' => $intPropId]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['name' => SORT_ASC]]
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
            'property_type_id' => $this->property_type_id,
            //'position' => $this->position,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'location_node_id', $this->location_node_id])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'description', $this->description])
			->andFilterWhere(['=', 'status', $this->status]);

        return $dataProvider;
    }

	 /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchChildNodes($params, $intParentId)
    {
        $query = LocationNodes::find()
				->where(['location_node_id' => $intParentId]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['name' => SORT_ASC]]
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
            'property_type_id' => $this->property_type_id,
            //'position' => $this->position,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'location_node_id', $this->location_node_id])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'description', $this->description])
			->andFilterWhere(['=', 'location_nodes.status', $this->status]);

        return $dataProvider;
    }
}
