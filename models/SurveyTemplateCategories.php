<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "survey_template_categories".
 *
 * @property integer $id
 * @property string $name
 */
class SurveyTemplateCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_template_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 38],
			[['name', 'status'],'trim'],
			['name', 'required', 'on'=>['create', 'update']],
			['name', 'unique', 'on'=>['create', 'update']],
			['name', 'match' ,'pattern'=>'/^[A-Za-z0-9-:_()&\' ]+$/u','message'=> INVALID_CAT_NAME, 'on'=>['create', 'update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Category',
			'status' => 'Status'
        ];
    }
}
