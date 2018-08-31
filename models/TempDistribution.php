<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "temp_distribution".
 *
 * @property integer $id
 * @property integer $contributor_id
 * @property integer $survey_id
 * @property string $quarter
 */
class TempDistribution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp_distribution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contributor_id', 'survey_id', 'quarter'], 'required'],
            [['contributor_id', 'survey_id'], 'integer'],
            [['quarter'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contributor_id' => 'Contributor ID',
            'survey_id' => 'Survey ID',
            'quarter' => 'Quarter',
        ];
    }
}
