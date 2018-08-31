<?php

namespace app\models\contributor;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\contributor\Surveyquarters;

/**
 * Surveyquarterssearch represents the model behind the search form about `app\models\Surveyquarters`.
 */
class Surveyquarterssearch extends Surveyquarters
{
    /**
     * @inheritdoc
     */
	 public $name;
    public function rules()
    {
        return [
            [['id', 'survey_template_id', 'distributable'], 'integer'],
            [['distributed', 'quarter', 'closed', 'deadline', 'created_at', 'updated_at','name'], 'safe'],
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
        $query = Surveyquarters::find();
		if(Yii::$app->controller->id=='contributor/surveyquarters' && Yii::$app->controller->action->id=='index')
		{
			$query->joinWith(['surveytemplatequestions']);		
		}
		else
		{
			$query->joinWith(['surveytemplates']);
		}




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
            'survey_quarters.id' => $this->id,
            'survey_template_questions.survey_template_id' => $this->survey_template_id,
            'distributable' => $this->distributable,
        ]);

        $query->andFilterWhere(['like', 'distributed', $this->distributed])
            ->andFilterWhere(['like', 'quarter', $this->quarter])
            ->andFilterWhere(['like', 'closed', $this->closed])
            ->andFilterWhere(['like', 'deadline', $this->deadline])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
