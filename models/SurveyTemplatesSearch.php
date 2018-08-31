<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use app\models\SurveyTemplates;

/**
 * SurveyTemplatesSearch represents the model behind the search form about `app\models\SurveyTemplates`.
 */
class SurveyTemplatesSearch extends SurveyTemplates
{
	public $category_name;
	public $status;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_template_category_id'], 'integer'],
            [['name', 'publication', 'contributor_type', 'category_name', 'status', 'category'], 'safe'],
            [['name','category_name'], 'trim']
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
    public function search($params, $quarter)
    {
	//echo '<pre/>';print_r($params);
        $query = SurveyTemplates::find()->select([
		'survey_templates.*',
		'survey_quarters.quarter as quarter_name',
		'survey_quarters.deadline as deadline',
		'survey_template_categories.name as category',
		'survey_quarters.id as quarter_id',
		'survey_quarters.distributed',
        'survey_quarters.distributable',
		]);
		$query->orderBy('name');
		$query->joinWith(['surveyCategory', 'surveyQuarter'])->where(['survey_quarters.quarter' => $quarter]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['category'] = [
			'asc' => [ 'survey_template_categories.name' => SORT_ASC ],
			'desc' => ['survey_template_categories.name' => SORT_DESC ]
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
            'survey_template_category_id' => $this->survey_template_category_id,
        ]);
		if($this->status == 0) {
			 //$this->status = '';
		}
		$query->andFilterWhere(['like', 'survey_templates.name', $this->name])
            ->andFilterWhere(['like', 'publication', $this->publication])
            ->andFilterWhere(['like', 'contributor_type', $this->contributor_type])
			->andFilterWhere(['like', 'survey_template_categories.name', $this->category])
			->andFilterWhere(['=', 'closed', $this->status]);

        return $dataProvider;
    }

    ##-------Fetch latest survey-----------##
	 public function getLatestSurvey($quarter)
    {
	//echo '<pre/>';print_r($params);
        $query = SurveyTemplates::find()->select([
		'survey_templates.*',
		'survey_quarters.quarter as quarter_name',
		'survey_quarters.deadline as deadline',
		'survey_template_categories.name as category',
		'survey_quarters.id as quarter_id',
		'survey_quarters.distributed',
		]);

		$query->joinWith(['surveyCategory', 'surveyQuarter'])->where(['survey_quarters.quarter' => $quarter]);
		//->limit(5)->all();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [ 'attributes' => ['name' => SORT_ASC]],
			'pagination' => [
        		'pageSize' => 5,
    		],
        ]);


        //$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

       return $dataProvider;
    }

    ##----- Reports to generate survey quarterly contributions----------##
    public function getQuarterlyContribution($params)
    {
        $queryCount =  (new \yii\db\Query())
                ->select(['count(*) as total'])
                ->from('survey_templates');
        $queryCount->innerJoin('survey_template_categories', 'survey_template_categories.id = survey_templates.survey_template_category_id' );
        $queryCount->innerJoin('survey_quarters', 'survey_quarters.survey_template_id = survey_templates.id' );

//print_r($totalCount);echo $totalCount[0]['total'];exit;
        $query = SurveyTemplates::find()->select([
         'survey_quarters.id as quarter_id',
        'survey_templates.*',
        'survey_quarters.quarter as quarter',
        'survey_template_categories.name as category',

        ]);
        $clause = 'where';
        if(isset($params['SurveyTemplatesSearch']['name'])) {
             $query->where(['like', 'survey_templates.name', trim($params['SurveyTemplatesSearch']['name'])]);
             $queryCount->where(['like', 'survey_templates.name', trim($params['SurveyTemplatesSearch']['name'])]);
             $clause = 'andWhere';
        }
        if(isset($params['SurveyTemplatesSearch']['category'])) {
             $query->$clause(['like', 'survey_template_categories.name', trim($params['SurveyTemplatesSearch']['category'])]);
            $queryCount->$clause(['like', 'survey_template_categories.name', trim($params['SurveyTemplatesSearch']['category'])]);
        }
        $query->innerJoinWith(['surveyCategory', 'surveyQuarter'] , false);

        // add conditions that should always apply here
            $totalCount =  $queryCount->all();
            $total =$totalCount[0]['total'];
            $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'totalCount' => intval($total),
            'sort' =>  ['defaultOrder' => ['name' => SORT_ASC]],
             'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $dataProvider->sort->attributes['id'] = [
            'asc' => [ 'survey_templates.id' => SORT_ASC ],
            'desc' => [ 'survey_templates.id' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['category'] = [
            'asc' => [ 'survey_template_categories.name' => SORT_ASC ],
            'desc' => ['survey_template_categories.name' => SORT_DESC ]
        ];
        $dataProvider->sort->attributes['name'] = [
            'asc' => [ 'survey_templates.name' => SORT_ASC ],
            'desc' => ['survey_templates.name' => SORT_DESC ]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }


}
