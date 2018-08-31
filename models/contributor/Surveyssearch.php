<?php

namespace app\models\contributor;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\contributor\Surveys;

/**
 * Surveyssearch represents the model behind the search form about `app\models\contributor\Surveys`.
 */
class Surveyssearch extends Surveys
{
    /**
     * @inheritdoc
     */
	public $name;
	public $quarter;
    public function rules()
    {
        return [
            [['id', 'survey_quarter_id', 'contributor_id', 'distributed', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'completed','survey_name','quarter'], 'safe'],

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
    public function search($params ,$searchQuarter, $hideNonDistributed = false)
    {
        $query = Surveys::find();
		$query->joinWith(['surveyquarters','surveyquarters.surveytemplates']);
        $query->where(['deleted' => '0']);
        if($searchQuarter !="") {
            $query->where(['quarter' => $searchQuarter]);
        }
        if($hideNonDistributed) {
            $query->where(['survey_quarters.distributed' => '1']);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['id' => SORT_DESC,]]
        ]);

		$dataProvider->sort->attributes['survey_name'] = [
        'asc' => ['survey_templates.name' => SORT_ASC],
        'desc' => ['survey_templates.name' => SORT_DESC],
    	];

		$dataProvider->sort->attributes['quarter'] = [
        'asc' => ['survey_quarters.quarter' => SORT_ASC],
        'desc' => ['survey_quarters.quarter' => SORT_DESC],
    	];

		$dataProvider->sort->attributes['deadline'] = [
        'asc' => ['survey_quarters.deadline' => SORT_ASC],
        'desc' => ['survey_quarters.deadline' => SORT_DESC],
    	];

        $this->load($params);
		/*if($this->completed=="")
		{
			$this->quarter = '';
		}*/

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

        $query->andFilterWhere(['like', 'survey_templates.name', trim($this->survey_name)])
			->andFilterWhere(['like', 'survey_quarters.quarter', $this->quarter])
            ->andFilterWhere(['like', 'completed', $this->completed]);
        return $dataProvider;
    }

	## function to get list of quarters for each year
	public function getQuartersList()
	{
		$currentYear = date('Y');
		$arrQuarters =  [];

		$startYear = 2010;

		while($currentYear >= $startYear) {
            ## For current year,need to find to total no. of months that have passed along with the current month
            if(date('Y') == $currentYear) {
                $totalmonth = date('n');
            } else {
                ## Else there will be always 12 months in a year
                $totalmonth = 12;
            }
            for($i = 1; $i < 5 ; $i++ ) {
                if(date('Y') == $currentYear) {
                    $current = ceil($totalmonth/3);
                } else {
                   $current = floor($totalmonth/3);
                }
                $totalmonth =abs($totalmonth - 3);
                ## current year may not have completed all quarters
                if($current == 0)
                    {break;}
                $arrQuarters[$currentYear.':'. $current] = $currentYear.':'. $current;


            }
            $currentYear--;
        }

		return $arrQuarters;
	}


}
