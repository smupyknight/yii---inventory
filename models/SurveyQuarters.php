<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "survey_quarters".
 *
 * @property integer $id
 * @property integer $survey_template_id
 * @property string $distributed
 * @property string $quarter
 * @property string $closed
 * @property string $deadline
 * @property string $created_at
 * @property string $updated_at
 * @property integer $distributable
 */
class SurveyQuarters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_quarters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_template_id', 'distributable'], 'integer'],
            //[['distributed', 'closed'], 'string', 'max' => 1],
            [['quarter'], 'string', 'max' => 6],
           // [['deadline', 'created_at', 'updated_at'], 'string', 'max' => 19],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_template_id' => 'Survey Template ID',
            'distributed' => 'Distributed',
            'quarter' => 'Quarter',
            'closed' => 'Closed',
            'deadline' => 'Deadline',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'distributable' => 'Distributable',
        ];
    }

    /**
    * function to get next quarter
    */
    public function getNextQuarter($strQuarter)
    {
        $quarterArr =explode(':', $strQuarter);
        $nextQuarter = '';
        if(!empty($quarterArr)) {
            if($quarterArr[1] != '4') {
                $nextQuarter = $quarterArr[0].':'.($quarterArr[1]+1);
            } else {
                $nextQuarter = ($quarterArr[0]+1).':1';
            }
        }
        return $nextQuarter;
    }

    ## Define Relation between Surveys & Contributors
    public function getCurrentsurvey()
    {
        return $this->hasOne(Surveys::ClassName(), ['quarter_id' => 'id']);
    }
}
