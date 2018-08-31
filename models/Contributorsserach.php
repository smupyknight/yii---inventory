<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contributors;

/**
 * Contributorsserach represents the model behind the search form about `app\models\Contributors`.
 */
class Contributorsserach extends Contributors
{

    /**
     * @inheritdoc
     */
	 public $name;
    public function rules()
    {
        return [
            [['id', 'company_id', 'user_id'], 'integer'],
            [['contributor_type', 'firstname', 'lastname', 'contact_number', 'alternative_contact_number', 'address', 'publication', 'disabled','name','contributor_name'], 'safe'],
            ['contributor_name', 'trim']
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
		      $query->joinWith(['companies']);

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

     public function searchSurveyContributor($params, $surveyNodes, $strContributorType , $quarterID, $isDistributed)
    {
        $query = (new \yii\db\Query())
                ->select(['contributors.*','distributed','completed'])
                ->from('contributors');

       $query->addSelect(["CONCAT(firstname, ' ', lastname) AS contributor_name"]);
       //$query->innerjoinWith(['contributorNodes', 'surveys']);
        $query->innerJoin('contributor_nodes', 'contributor_nodes.contributor_id = contributors.id' );
        // var_dump($quarterID);
        // die();
        if($isDistributed == '1') {
            $query->innerJoin('surveys', 'surveys.contributor_id = contributors.id and survey_quarter_id='.$quarterID);
        } else {
            $query->leftJoin('surveys', 'surveys.contributor_id = contributors.id and survey_quarter_id='.$quarterID);
        }
        //$query->onCondition();
        if($strContributorType!= "" && $strContributorType!="Both") {
                $query->where(['contributor_type' => $strContributorType]);
        }
        if(!empty($surveyNodes->nodesArr) && !empty($surveyNodes->propertyArr)) {
            $query->andWhere(['IN', 'location_node_id', $surveyNodes->nodesArr]);
            $query->andWhere(['IN', 'property_type_id', $surveyNodes->propertyArr]);
        }
         $this->load($params);
        if($this->contributor_name!= "") {
            $query->andWhere('firstname LIKE "%' . $this->contributor_name . '%" ' .
                'OR lastname LIKE "%' . $this->contributor_name . '%"'.
                'OR CONCAT_WS (" ",trim(firstname),trim(lastname)) LIKE "%' . $this->contributor_name . '%"'
            );
        }
        $query->groupBy('contributors.id');
//if($_SERVER['REMOTE_ADDR']=='195.168.77.18') {
 //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit;
 //die();
//}
         // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['contributor_name'],
                'defaultOrder' => [
                    'contributor_name' => SORT_ASC,
                    ]
                ] ,
             'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $dataProvider->sort->attributes['contributor_name'] = [
            'asc' => ['firstname' => SORT_ASC, 'lastname' => SORT_ASC],
            'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
            //'label' => 'Full Name',
        ];
        //$dataProvider = $provider->getModels();



        if (empty($surveyNodes->nodesArr) || empty($surveyNodes->propertyArr)) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'contributor_type', $this->contributor_type])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'alternative_contact_number', $this->alternative_contact_number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'distribution_method', $this->distribution_method])
            ->andFilterWhere(['like', 'publication', $this->publication])
            ->andFilterWhere(['like', 'disabled', $this->disabled]);
            //print_r($dataProvider);exit;

            //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit;
        return $dataProvider;
    }
}
