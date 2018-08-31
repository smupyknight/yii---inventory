<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\Surveys;

/**
 * SurveySearch represents the model behind the search form about `app\models\Surveys`.
 */
class SurveySearch extends Surveys
{
    public $quarter;
    public $publication;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_quarter_id', 'contributor_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'completed', 'contributor_name'], 'safe'],
            ['contributor_name','trim']
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
    public function search($params, $intQuarterId)
    {
        $query = Surveys::find()
				->select('surveys.*,
				contributors.distribution_method')
				->addSelect(["CONCAT(firstname, ' ', lastname) AS contributor_name"])
				->where(['survey_quarter_id' => $intQuarterId]);

        // add conditions that should always apply here
		$query->joinWith(['contributors']);
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
  			'sort' => [
  				'attributes' => ['contributor_name'],
  				'defaultOrder' => [
  					'contributor_name' => SORT_ASC,
  					]
  				]
        ]);

		$dataProvider->sort->attributes['contributor_name'] = [
			'asc' => ['firstname' => SORT_ASC, 'lastname' => SORT_ASC],
			'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
			'label' => 'Full Name',
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
        'survey_quarter_id' => $this->survey_quarter_id,
        'contributor_id' => $this->contributor_id,
        'distributed' => $this->distributed,
        'deleted' => $this->deleted,
    ]);
		$query->andWhere('firstname LIKE "%' . $this->contributor_name . '%" ' .
			'OR lastname LIKE "%' . $this->contributor_name . '%"'.
			'OR CONCAT_WS (" ",firstname,lastname) LIKE "%' . $this->contributor_name . '%"'
		);
    $query->andFilterWhere(['like', 'created_at', $this->created_at])
        ->andFilterWhere(['like', 'updated_at', $this->updated_at])
        ->andFilterWhere(['like', 'completed', $this->completed]);


    return $dataProvider;
    }

    ##------Get excluded contributors ---------##
    public function getExcludedContributors($params)
    {
        set_time_limit(0);
        $query = (new \yii\db\Query())
                ->select(['data_fields.value','data_fields.exclude_reason','survey_templates.name','survey_template_id','survey_template_question_nodes.survey_template_question_id','survey_quarters.quarter','survey_template_question_nodes.name as node'])
                ->where(['data_fields.included' => '0'])
                ->from('surveys');
        $query->addSelect(["CONCAT(firstname, ' ', lastname) AS contributor_name"]);
        $query->innerJoin('data_fields', 'data_fields.survey_id = surveys.id' );
        $query->innerJoin('survey_template_question_nodes', 'survey_template_question_nodes.id = data_fields.survey_template_question_node_id' );
        //$query->innerJoin('survey_template_questions', 'survey_template_questions.id = survey_template_question_nodes.survey_template_question_id' );
        $query->innerJoin('contributors', 'contributors.id = surveys.contributor_id' );
        $query->innerJoin('survey_quarters', 'survey_quarters.id = surveys.survey_quarter_id' );
        $query->innerJoin('survey_templates', 'survey_templates.id = survey_quarters.survey_template_id' );

        //$query->groupBy('survey_template_question_nodes.id');

        $query->andFilterWhere([
            'id' => $this->id,
            'survey_quarter_id' => $this->survey_quarter_id,
            'contributor_id' => $this->contributor_id,
            'distributed' => $this->distributed,
            'deleted' => $this->deleted,
        ]);
        $this->load($params);
        //echo $this->contributor_name;exit;
        $query->andWhere('firstname LIKE "%' . trim($this->contributor_name) . '%" ' .
            'OR lastname LIKE "%' . trim($this->contributor_name) . '%"'.
            'OR CONCAT_WS (" ",firstname,lastname) LIKE "%' . trim($this->contributor_name) . '%"'
        );

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => ['contributor_name','survey_templates.name','survey_quarters.quarter','survey_template_question_id','node'],
                /*'defaultOrder' => [
                    'contributor_name' => SORT_ASC,
                    'survey_templates.name' => SORT_ASC,
                    'survey_quarters.quarter' => SORT_ASC,
                    'survey_template_question_id' => SORT_ASC,
                    'node' => SORT_ASC,

                    ]*/
                ],
            'pagination' => [
            'pageSize' => 50,
            ],
        ]);
        $arrData = [];
        $data = $dataProvider->allModels;
        $sequence = 1;
        //echo '<pre/>';print_r($dataProvider->allModels);exit;
        for($i=0;$i<count($data);$i++) {

            if($i>0) {
                if($data[$i]['survey_template_id'] == $data[$i-1]['survey_template_id']) {
                    if($data[$i]['survey_template_question_id'] != $data[$i-1]['survey_template_question_id']) {
                    $sequence++;
                }
                } else {
                     $sequence = 1;
                }

            }
                $arrData[] = [
                'contributor_name' => $data[$i]['contributor_name'],
                'value' => $data[$i]['value'],
                'exclude_reason' => $data[$i]['exclude_reason'],
                'name' => $data[$i]['name'],
                'survey_template_question_id' => $sequence,
                'quarter' => $data[$i]['quarter'],
                'node' => $data[$i]['node'],
                ];
        }
        $dataProvider->allModels = $arrData;
            //$dataProvider->allModels[0]['value'] = 3;
            //echo '<pre/>';print_r($dataProvider->allModels);exit;

        return $dataProvider;
    }

    ## -----Get erratic contributors -------- ##
    public function getErraticcontributors($params , $contributors)
    {
        set_time_limit(0);
        //ini_set('max_execution_time', 300);
        ini_set('max_execution_time', 0);
        $data = [];
        if(count($contributors)>0) {
            $connection = \Yii::$app->db;

            foreach($contributors as $cont) {
                //echo $cont['id'];exit;
                $users = [];
            $model = $connection->createCommand("SELECT group_concat(`quarter`) as quarter,sum(`tot`) as total_survey_assigned,
                sum(`completed`) as incomplete_survey, sum(`data`) as missed_survey_data
                from (
                    select `survey_quarters`.`quarter`,`survey_quarter_id`,count(`survey_template_id`) as tot ,
                    COUNT(IF(completed='0',1, NULL)) 'completed' ,COUNT(`data_fields`.`id`) 'data'
                    from `surveys`
                    inner join `survey_quarters` on `survey_quarters`.`id` = `surveys` .`survey_quarter_id`
                    left join `data_fields` on `surveys`.`id` = `data_fields`.`survey_id` and `included` = 0
                    where `surveys`.`contributor_id` = ".$cont['id']."
                    and `closed` = '1'
                    and `survey_quarters`.`distributed` = '1'
                group by `survey_quarters`.`quarter`
                order by `survey_quarters`.`quarter` DESC limit 2) A
  ");

                /*$model = $connection->createCommand("SELECT `surveys`.*,CONCAT(firstname, ' ', lastname) AS contributor_name,
                    (select sum(`cnt`) from (
                        select count('survey_template_id') as cnt from `survey_quarters`
                        inner join `surveys` as `x` on `survey_quarters`.`id` = `x`.`survey_quarter_id`
                        where
                         `survey_quarters`.`distributed`= '1' and
                         `contributor_id` = ".$cont['id']." and
                         `survey_quarters`.`closed` = '1' and
                         `completed` = '0'
                         group by `quarter`  order by `quarter` desc limit 2) q)
                as `missed`,(
                SELECT sum(`total`) from (
                    SELECT count(*) as total FROM `data_fields`
                    inner join `surveys` on `surveys`.`id` = `data_fields`.`survey_id`
                    where
                    `data_fields`.`contributor_id` = ".$cont['id']." and
                    `included` = 0
                    group by `quarter` order by `quarter` desc limit 2) t)
                as miss ,
               (select sum(`count`) from (select count('survey_template_id') as count from `surveys` inner join `survey_quarters` on `survey_quarters`.`id` = `surveys`.`survey_quarter_id`
                where
                `survey_quarters`.`distributed`= '1' and
                `contributor_id` = ".$cont['id']." and
                `closed` = '1'
                group by `quarter`  order by `quarter` desc limit 2
                ) t) as final
                from `surveys`
                inner join `contributors` on `contributors`.`id` = `surveys`.`contributor_id`
                where `contributor_id` = ".$cont['id']."
                group by `contributor_id`
                ");*/
                $users = $model->queryOne();
                //echo '<pre/>';print_r($users);
                    if($users['quarter'] != "") {
                        $data [] = [
                                'contributor_name' => $cont['contributor_name'],
                                'quarter' => $users['quarter'],
                                'total_survey_assigned' => $users['total_survey_assigned'],
                                'incomplete_survey' => $users['incomplete_survey'],
                                'missed_survey_data' => $users['missed_survey_data'],
                                'contributor_id' => $cont['id'],
                                'user_id' => $cont['user_id'],
                                'disabled' => $cont['disabled']
                        ];

                    }
                }
            }
            //echo '<pre/>';print_r($data);exit;
            $dataProvider = new ArrayDataProvider([
            'allModels' => $data,

            'sort' => [
                'attributes' => ['contributor_name'],

                ],
            'pagination' => [
            'pageSize' => 50,
            ],
        ]);
        return $dataProvider;
    }

    ##-----GEt Inactive Contributors----------##
    public function getInactivecontributors($params , $contributors)
    {
        ini_set('max_execution_time', 300);
        $data = [];
        if(count($contributors)>0) {
            $connection = \Yii::$app->db;

            foreach($contributors as $cont) {
                $users = [];
            $model = $connection->createCommand("SELECT group_concat(`quarter`) as quarter,sum(`tot`) as total_survey_assigned,
                sum(`completed`) as incomplete_survey
                from (
                    select `survey_quarters`.`quarter`,count(`survey_template_id`) as tot ,
                    COUNT(IF(completed='0',1, NULL)) 'completed'
                    from `surveys`
                    inner join `survey_quarters` on `survey_quarters`.`id` = `surveys` .`survey_quarter_id`
                    where `surveys`.`contributor_id` = ".$cont['id']."
                    and `closed` = '1'
                    and `survey_quarters`.`distributed` = '1'
                group by `survey_quarters`.`quarter`
                order by `survey_quarters`.`quarter` DESC limit 6) A
  ");
                $users = $model->queryOne();
              $quart = explode(',',$users['quarter']);
                    if(count($quart) == 6) {
                        if($users['total_survey_assigned'] == $users['incomplete_survey'])
                        $data [] = [
                                'contributor_name' => $cont['contributor_name'],
                                'quarter' => $users['quarter'],
                                'total_survey_assigned' => $users['total_survey_assigned'],
                                'contributor_id' => $cont['id'],
                                'user_id' => $cont['user_id'],
                                'disabled' => $cont['disabled']
                        ];

                    }
                }
            }
            //echo '<pre/>';print_r($data);exit;
            $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['contributor_name'],
                'defaultOrder' => [
                    'contributor_name' => SORT_ASC,
                    // 'survey_templates.name' => SORT_ASC,
                    // 'survey_quarters.quarter' => SORT_ASC,
                    // 'survey_template_question_id' => SORT_ASC,
                    // 'node' => SORT_ASC,

                    ]
                ],
            'pagination' => [
            'pageSize' => 50,
            ],
        ]);
            $dataProvider->sort->attributes['contributor_name'] = [
            'asc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
            'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
            'label' => 'Full Name',
        ];
        return $dataProvider;
    }


    ##-------Get individual contributions------------##
    public function getIndividualContributions($params ,$node_id , $quarter)
    {
       $query = (new \yii\db\Query())
                ->select(['data_fields.value'])
                ->where([
                    'data_fields.included' => '1',
                    'data_fields.quarter' => $quarter ,
                    'survey_template_question_node_id' => $node_id,
                    ])
                ->andWhere(['<>','value',''])
                ->from('data_fields');
        $query->addSelect(["CONCAT(firstname, ' ', lastname) AS contributor_name"]);
        $query->innerJoin('surveys', 'data_fields.survey_id = surveys.id' );
        $query->innerJoin('contributors', 'contributors.id = surveys.contributor_id' );


        //$query->groupBy('survey_template_question_nodes.id');


        $this->load($params);
        //echo $this->contributor_name;exit;
        $query->andWhere('firstname LIKE "%' . trim($this->contributor_name) . '%" ' .
            'OR lastname LIKE "%' . trim($this->contributor_name) . '%"'.
            'OR CONCAT_WS (" ",firstname,lastname) LIKE "%' . trim($this->contributor_name) . '%"'
        );

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => ['contributor_name','survey_templates.name','survey_quarters.quarter','survey_template_question_id','node'],

                ],
            'pagination' => [
                    'pageSize' => 10,
            ],
        ]);
        $dataProvider->sort->attributes['contributor_name'] = [
            'asc' => ['firstname' => SORT_ASC, 'lastname' => SORT_ASC],
            'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
            'label' => 'Full Name',
        ];

        return $dataProvider;
    }

    ##------Get publicatio nwise contribution---------##
    public function getPublicationContributions($params)
    {
      $query = (new \yii\db\Query())
                ->select(["CONCAT(firstname, ' ', lastname) AS contributor_name"])
                ->where([
                    'publication' => $params['SurveySearch']['publication'],
                    'disabled' => '1'
                    ])
                ->from('contributors');
        $query->innerJoin('surveys', 'contributors.id = surveys.contributor_id' );
        if(isset($params['SurveySearch']['quarter']) && $params['SurveySearch']['quarter']!="") {
            $query->andWhere(['quarter'  => $params['SurveySearch']['quarter']]);
            $query->innerJoin('survey_quarters', 'survey_quarters.id = surveys.survey_quarter_id' );
        }


        $query->groupBy('contributors.id');


        $this->load($params);
        //echo $this->contributor_name;exit;
        if($this->contributor_name != "") {
            $query->andWhere('firstname LIKE "%' . trim($this->contributor_name) . '%" ' .
                'OR lastname LIKE "%' . trim($this->contributor_name) . '%"'.
                'OR CONCAT_WS (" ",firstname,lastname) LIKE "%' . trim($this->contributor_name) . '%"'
            );
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => ['contributor_name'],
                'defaultOrder' => ['contributor_name' => SORT_ASC]

                ],
            'pagination' => [
                    'pageSize' => 50,
            ],
        ]);
        $dataProvider->sort->attributes['contributor_name'] = [
            'asc' => ['firstname' => SORT_ASC, 'lastname' => SORT_ASC],
            'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
            'label' => 'Full Name',
        ];

        return $dataProvider;
    }

    ##------Get surveys 'n values---------##
    public function getSurveyans($id,$quarter,$node_id)
    {
        $query = (new \yii\db\Query())
                 ->select(['heading', 'value']) //'avg(value) as average_val','survey_template_question_node_id','data_field_template_id','count(surveys.id) as tot_cnt'
                 ->from('surveys')
                 ->leftJoin('data_fields', 'data_fields.survey_id = surveys.id' )
                 ->leftJoin('survey_quarters', 'survey_quarters.id = surveys.survey_quarter_id' )
                 ->leftJoin('data_field_templates', 'data_field_templates.id = data_field_template_id' )
                 ->where([ 'survey_template_id' => $id , 'survey_quarters.quarter' => $quarter])
                 ->andWhere(['<>','value',''])
                 ->andWhere(['included'=> '1'])
                 ->andWhere(['IN', 'survey_template_question_node_id', $node_id])
                 ->groupBy(['survey_template_question_node_id','data_field_template_id'])
                 ->orderBy('');
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => ['heading'],
                'defaultOrder' => ['heading' => SORT_ASC]

                ],
            'pagination' => [
                    'pageSize' => 50,
            ],
        ]);

        return $dataProvider;
    }
}
