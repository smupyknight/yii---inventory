<?php

namespace app\models\contributor;

use Yii;
use app\models\contributor\DataFields;

/**
 * This is the model class for table "surveys".
 *
 * @property integer $id
 * @property integer $survey_quarter_id
 * @property integer $contributor_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $completed
 * @property integer $distributed
 * @property integer $deleted
 */
class Surveys extends \yii\db\ActiveRecord
{
    public $survey_name;
    public $average_val;
    public $data_field_template_id;
    public $survey_template_question_node_id;
    public $tot_cnt;
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'surveys';
    }
	 
	 ## Define Relation between Surveys & Surveyquarters
	 public function getSurveyquarters()
	{
		return $this->hasOne(Surveyquarters::className(), ['id' => 'survey_quarter_id']);
	}
	
	## Define Relation between Surveys & Contributors
	public function getContributors()
	{
		return $this->hasOne(Contributors::ClassName(), ['id' => 'contributor_id']);
	}
	

    ## Define Relation between Surveys & Contributors
    public function getDatafields()
    {
        return $this->hasOne(DataFields::ClassName(), ['survey_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_quarter_id', 'contributor_id', 'distributed', 'deleted'], 'integer'],
            [['created_at', 'updated_at'], 'string', 'max' => 19],
            [['completed'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_quarter_id' => 'Survey Quarter ID',
            'contributor_id' => 'Contributor ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'completed' => 'Status',
            'distributed' => 'Distributed',
            'deleted' => 'Deleted',
			'name'=>'Name',
			'quarter'=>'Quarter',
			'deadline'=>'Deadline',
        ];
    }

  ## check if survey is new i.e if contributor has answered or not
    public static function IsNewSurvey($intsurveyID, $boolCompleted)
    {
        $intCount = 0;
        $intCount = DataFields::find()
                ->where(['survey_id' => $intsurveyID])
                ->count();
        
        if($intCount == 0) {
            return '<i class="fa fa-envelope "></i>';
        } else if($boolCompleted == 1) {
            return '<i class="fa fa-mail-forward "></i>';
        } else {
             return '<i class="fa fa-envelope-o "></i>';
        }
        
    }
}
