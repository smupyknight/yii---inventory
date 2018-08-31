<?php

namespace app\models\contributor;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\contributor\Contributors;

/**
 * Contributorsserach represents the model behind the search form about `app\models\Contributors`.
 */
class Contributorsserach extends Contributors
{
    /**
     * @inheritdoc
     */
	 //public $name;
    public function rules()
    {
        return [
            [['id', 'company_id', 'user_id'], 'integer'],
            [['contributor_type', 'firstname', 'lastname', 'contact_number', 'alternative_contact_number', 'address', 'distribution_method', 'publication', 'disabled'], 'safe'],		//,'name'
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
        $query = Contributors::find();
		//$query->joinWith(['companies']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'contributor_type', $this->contributor_type])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'alternative_contact_number', $this->alternative_contact_number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'distribution_method', $this->distribution_method])
            ->andFilterWhere(['like', 'publication', $this->publication])
            ->andFilterWhere(['like', 'disabled', $this->disabled]);

        return $dataProvider;
    }
}
