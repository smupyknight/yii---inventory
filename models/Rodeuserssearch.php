<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rodeusers;

/**
 * Rodeuserssearch represents the model behind the search form about `app\models\Rodeusers`.
 */
class Rodeuserssearch extends Rodeusers
{
    /**
     * @inheritdoc
     */
	public $fullName;
	public $contributor_type;
	public $distribution_method;
	public $company_id;
	public $companies;

    public function rules()
    {

        return [
            [['id'], 'integer'],
            [['contributorstatus', 'distribution_method','login', 'name', 'email', 'crypted_password', 'salt', 'created_at', 'updated_at', 'remember_token', 'remember_token_expires_at', 'disabled', 'confirmation','fullName','contributor_type'], 'safe'],
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

		if(isset($params['company_id']) && !empty($params['company_id']))
		{
			 $query = Rodeusers::find()->where(['company_id'=>$params['company_id']]);
		}
		else
		{
			 $query = Rodeusers::find()->where(['!=', 'role', 'Administrator']);
		}

    //$query = Rodeusers::find();
		$query->joinWith(['contributors']);

    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 50,
        ],
        'sort'=> ['defaultOrder' => ['fullName'=>SORT_ASC]]
    ]);

		$dataProvider->sort->attributes['fullName'] = [
        'asc' => ['contributors.firstname' => SORT_ASC],
        'desc' => ['contributors.firstname' => SORT_DESC],
    	];

		$dataProvider->sort->attributes['distribution_method'] = [
        'asc' => ['contributors.distribution_method' => SORT_ASC],
        'desc' => ['contributors.distribution_method' => SORT_DESC],
    	];

		$dataProvider->sort->attributes['publication'] = [
        'asc' => ['contributors.publication' => SORT_ASC],
        'desc' => ['contributors.publication' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'crypted_password', $this->crypted_password])
            ->andFilterWhere(['like', 'salt', $this->salt])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['=',    'contributors.contributor_type', $this->contributor_type])
	          ->andFilterWhere(['=',    'contributors.distribution_method', $this->distribution_method])
	          ->andFilterWhere(['=',    'contributors.company_id', $this->company_id])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'remember_token', $this->remember_token])
            ->andFilterWhere(['like', 'remember_token_expires_at', $this->remember_token_expires_at])
            ->andFilterWhere(['=', 'users.disabled', $this->disabled])
			      ->andFilterWhere(['or',['like', 'contributors.firstname', $this->fullName],['like', 'contributors.lastname', $this->fullName],['like','CONCAT_WS (" ",trim(firstname),trim(lastname))',$this->fullName]])
            ->andFilterWhere(['like', 'confirmation', $this->confirmation]);
        return $dataProvider;
    }
}
