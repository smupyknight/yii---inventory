<?php

namespace app\models;
use app\models\SurveyTemplates;
use Yii;

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
	public $contributor_name;
	public $distribution_method;
    public $data_field_template_id;
    public $survey_template_question_node_id;
    public $tot_cnt;
    public $average_val;
    public $sd;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'surveys';
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
            'completed' => 'Completed',
            'distributed' => 'Distributed',
            'deleted' => 'Deleted',
			'contributor_name' => 'Contributor'
        ];
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
	
	## Check whether survey is distributed or not,if no,hide completed col
	public static function checkIsDistributed($data)
	{ 
		if($data['distributed'] == 1)
		 { return true;}
		 else 
		 { return false;}
	}

    
	
	
}
