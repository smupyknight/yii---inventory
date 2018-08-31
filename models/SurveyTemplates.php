<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\data\ActiveDataProvider;



/**
 * This is the model class for table "survey_templates".
 *
 * @property integer $id
 * @property string $name
 * @property integer $survey_template_category_id
 * @property string $publication
 * @property string $contributor_type
 */
class SurveyTemplates extends \yii\db\ActiveRecord
{
	public $quarter_name;
	public $deadline;
	public $category;
	public $quarter;
	public $status;
	public $quarter_id;
	public $closed;
	public $distributed;
	public $isDistributable;
	public $distributable;
	public $heading;
	public $survey_question;


	 /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_templates';
    }

    public function afterFind(){
       $this->oldAttributes = $this->attributes;
       return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
    	if(Yii::$app->controller->action->id == 'update') {
    		/*if(isset($this->oldAttributes['name']) && $this->name != $this->oldAttributes['name']){
				// The attribute is changed. Do something here...
			}*/
			$strChanges = '';//print_r($changedAttributes);
			foreach($changedAttributes as $k=>$v) {
				if($this->$k != $v ) {
					if($strChanges == "") {
						$strChanges = "---\n";
					}
					$strChanges .=$k." :\n-".$v."\n-".$this->$k.PHP_EOL;
				}

			}
			if($strChanges != "") {
				$intVersion = 1;
				$maxVersionArr = Audits::find()->select('max(version) as max_version')
										 ->where([
										 	'auditable_id' => $this->id,
										 	'auditable_type' => 'SurveyTemplate'
										 	 ])
										 ->asArray()
										 ->One();
						if($maxVersionArr['max_version'] != '') {
							$intVersion = $maxVersionArr['max_version'] + 1;
						}
						$connection = Yii::$app->db;
						$connection->createCommand()->insert('audits', [
					    	'auditable_type' => 'SurveyTemplate',
					    	'created_at' => new Expression('NOW()'),
					    	'user_type' => 'User',
					    	'action' => 'update',
					    	'version' => $intVersion,
					    	'username' => '',
					    	'auditable_id' => $this->id,
					    	'user_id' => Yii::$app->user->identity->id,
					    	'changes' => $strChanges
						])
						->execute();
			}

		}
	    return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_template_category_id'], 'integer'],
            [['name'], 'string', 'max' => 62],
            [['publication'], 'string', 'max' => 18],
            [['contributor_type'], 'string', 'max' => 6],
			[['name', 'publication', 'contributor_type', 'survey_template_category_id'], 'trim'],
			[['name', 'publication', 'contributor_type', 'survey_template_category_id'], 'required', 'on'=> ['create', 'update']],
			//['name', 'match', 'pattern'=>'/^[A-Za-z]+$/u']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Survey Name',
            'survey_template_category_id' => 'Survey Category',
            'publication' => 'Publication',
            'contributor_type' => 'Contributor Type',
			'category_name' => 'Category',
			'status' => 'Status',
			'quarter_name' => 'Quarter'
        ];
    }

	## Define Relation between Category & Survey
	public function getSurveyCategory()
	{
		return $this->hasOne(SurveyTemplateCategories::ClassName(), ['id' => 'survey_template_category_id']);
	}

	## Define Relation between Quarters & Survey
	public function getSurveyQuarter()
	{
		return $this->hasOne(SurveyQuarters::ClassName(), ['survey_template_id' => 'id']);
	}

	## Define Relation between Quarters & Survey
	public function getSurveys()
	{
		return $this->hasOne(Surveys::ClassName(), ['survey_template_id' => 'id']);
	}

	## Define Relation between Quarters & Survey
	public function getSurveyQuestions()
	{
		return $this->hasMany(SurveyTemplateQuestions::ClassName(), ['survey_template_id' => 'id']);
	}

	## function to get list of quarters for each year
	public function getQuartersList()
	{
		$currentYear = date('Y');
		$arrQuarters =  [];

		$startYear = 2010;
		/*while($startYear <= $currentYear) {
			## For current year,need to find to total no. of months that have passed along with the current month
			if($startYear == $currentYear) {
				$totalmonth = date('n');
			} else {
				## Else there will be always 12 months in a year
				$totalmonth = 12;
			}
			for($i = 1; $i < 5 ; $i++ ) {
				$current = floor($totalmonth/3);
				$totalmonth =$totalmonth - 3;
				## current year may not have completed all quarters
				if($current == 0)
					{break;}
				$arrQuarters[$startYear.':'. $current] = $startYear.':'. $current;


			}
			$startYear++;
		}*/
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

	## function to get list of publication
	public function getPublicationList()
	{
		$arrPublication = [
			'Both' => 'Both',
			'Rode Report' => 'Rode Report',
			'Rode Retail Report' => 'Rode Retail Report'];
		return 	$arrPublication;
	}
	## Get Publication List
	public static function fetchsurveyDetail($quarterId)
	{
		$SurveyData = static::find()->select(['survey_templates.*','deadline','survey_quarters.id as quarter_id','closed'])->joinWith(['surveyQuarter'])->where(['survey_quarters.id' => $quarterId ])->one();
		return ($SurveyData);
	}

	## Get Category Listing for dropdown
	public static function getCategoryList()
	{
		$catList = SurveyTemplateCategories::find()->select(['id','name'])->where(['status' => 'Active'])->orderBy('name')->asArray()->all();
		$arrCatList = ArrayHelper::map($catList, 'id', 'name');
		return $arrCatList;
	}

	## Get Contributor Type List
	public function getContrTypeList()
	{
		$arrContrList = [
			'Broker' => 'Broker',
			'Owner' => 'Owner',
			'Both' => 'Both'];
		return 	$arrContrList;
	}

	##----- List out all the surveys -----##
    public function searchOutputtables($id, $qid)
    {
    	//heading as publication bcz relational table's property was not accessbible
    	 /*$data = SurveyTemplates::find()
    	 		->select(['question','survey_template_questions.id'])
				->innerJoinWith(['surveyQuestions', 'surveyQuestions.outputtables', 'surveyQuestions.propertyTypes'], false)
				->where(['survey_templates.id' => $id])
				->orderBy('survey_template_questions.id')
				->asArray()
				->all();*/
		$query = new \yii\db\Query;
		$query->select(['survey_templates.id','survey_template_questions.id as qid','heading','sub_heading','property_types.name','output_tables.id as out_id']);
		$query->from('survey_templates');
		$query->where(['survey_templates.id' => $id]);
		$query->innerJoin('survey_template_questions' , 'survey_templates.id =survey_template_questions.survey_template_id');
		$query->innerJoin('output_tables','survey_template_questions.id = output_tables.survey_template_question_id');
		$query->innerJoin('property_types','survey_template_questions.property_type_id = property_types.id');
		$query->orderBy('survey_template_questions.id');
		$data = $query->all();
		$arrData = [];
		$sequence = 1;
		## Add 1 more column sequence groub by question id
		for($i=0;$i<count($data);$i++) {

			if($i>0) {
				if($data[$i]['qid'] != $data[$i-1]['qid']) {
					$sequence++;
				}

			}
				$arrData[] = [
				'id' => $data[$i]['id'],
				'qid' => $data[$i]['qid'],
				'heading' => $data[$i]['heading'],
				'sub_heading' => $data[$i]['sub_heading'],
				'name' => $data[$i]['name'],
				'sequence' => $sequence,
				'out_id' => $data[$i]['out_id'],
				];
		}//echo '<pre/>';print_r($arrData);exit;
       return $arrData;
    }

    ##------ Get comments-----##
    public function getComments($id, $quarter)
    {
    	$query = new \yii\db\Query;
		$query->select(['contributors.user_id','firstname','lastname','body','survey_comments.created_at']);
		$query->from('survey_quarters');
		$query->where(['survey_template_id' => $id, 'contributors.quarter' => $quarter]);
		$query->innerJoin('surveys' , 'surveys.survey_quarter_id =survey_quarters.id');
		$query->innerJoin('survey_comments','survey_comments.survey_id = surveys.id');
		$query->innerJoin('contributors','contributors.id = survey_comments.contributor_id');
		$query->orderBy('survey_comments.created_at DESC');
		$data = $query->all();//echo '<pre/>';print_r($data);exit;
		return $data;
    }




}
