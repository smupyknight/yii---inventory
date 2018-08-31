<?php

namespace app\models;

use Yii;
use app\models\PropertyTypes;

/**
 * This is the model class for table "survey_template_node_categories".
 *
 * @property string $id
 * @property string $name
 * @property string $survey_template_id
 * @property string $active
 */
class SurveyTemplateNodeCategories extends \yii\db\ActiveRecord
{
	public $property_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_template_node_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['id', 'name', 'survey_template_id', 'active'], 'string', 'max' => 10],
			['property_type_id', 'required'],
			['property_type_id', 'unique', 'message' => 'Name has already been taken.'],
			[['property_type_id','active'], 'trim']
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
            'survey_template_id' => 'Survey Template ID',
            'active' => 'Active',
			'property_type_id' => 'Name'
        ];
    }
	
	##--------Define Relation between property type & Node categories-----##
	public function getNodeProperty()
	{
		return $this->hasOne(PropertyTypes::className(), ['id' => 'property_type_id']);
	}
}
