<?php

namespace app\models\contributor;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\contributor\Surveytemplatequestions;

/**
 * Surveytemplatequestionssearch represents the model behind the search form about `app\models\Surveytemplatequestions`.
 */
class Surveytemplatequestionssearch extends Surveytemplatequestions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_template_id', 'property_type_id'], 'integer'],
            [['question', 'information', 'old_type', 'created_at', 'use_categories'], 'safe'],
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
    public function search($params ,$intSurveyTemplateId)
    {
        $query = Surveytemplatequestions::find()
        ->where(['survey_template_id' => $intSurveyTemplateId]);

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
            'survey_template_id' => $this->survey_template_id,
            'property_type_id' => $this->property_type_id,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'information', $this->information])
            ->andFilterWhere(['like', 'old_type', $this->old_type])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'use_categories', $this->use_categories]);

        return $dataProvider;
    }
}
