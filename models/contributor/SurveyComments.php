<?php

namespace app\models\contributor;

use Yii;

/**
 * This is the model class for table "survey_comments".
 *
 * @property integer $id
 * @property string $body
 * @property integer $survey_id
 * @property integer $contributor_id
 * @property string $created_at
 * @property string $updated_at
 */
class SurveyComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['survey_id', 'contributor_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['body'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'body' => 'Body',
            'survey_id' => 'Survey ID',
            'contributor_id' => 'Contributor ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
