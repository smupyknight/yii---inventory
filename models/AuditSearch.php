<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Audits;

/**
 * AuditSearch represents the model behind the search form about `app\models\Audits`.
 */
class AuditSearch extends Audits
{
   
   public $updatedBy; 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'auditable_id', 'user_id', 'version'], 'integer'],
            [['auditable_type', 'user_type', 'username', 'action', 'changes', 'created_at', 'updatedBy'], 'safe'],
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
    public function search($params, $int_auditable_id, $strAuditableType)
    {
        $query = Audits::find()
                ->where([
                            'auditable_id' => $int_auditable_id, 
                            'auditable_type' => $strAuditableType
                        ]);

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
            'auditable_id' => $this->auditable_id,
            'user_id' => $this->user_id,
            'version' => $this->version,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'auditable_type', $this->auditable_type])
            ->andFilterWhere(['like', 'user_type', $this->user_type])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'changes', $this->changes]);

        return $dataProvider;
    }

    
}
