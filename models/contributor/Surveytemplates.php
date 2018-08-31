<?php

namespace app\models\contributor;

use Yii;

/**
 * This is the model class for table "survey_templates".
 *
 * @property integer $id
 * @property string $name
 * @property integer $survey_template_category_id
 * @property string $publication
 * @property string $contributor_type
 */
class Surveytemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'survey_template_category_id'], 'integer'],
            [['name'], 'string', 'max' => 62],
            [['publication'], 'string', 'max' => 18],
            [['contributor_type'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'survey_template_category_id' => 'Survey Template Category ID',
            'publication' => 'Publication',
            'contributor_type' => 'Contributor Type',
        ];
    }
}
