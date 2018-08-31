<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_types".
 *
 * @property integer $id
 * @property string $name
 */
class PropertyTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 60],
			['name', 'required',  'on'=>['create', 'update']],
			['name', 'unique',  'on'=>['create', 'update']],
			[['name','id','status'], 'trim'],
			['name', 'match' ,'pattern'=>'/^[A-Za-z0-9-:_().&\' ]+$/u','message'=> INVALID_NAME, 
			'on'=>['create', 'update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Property Type',
			'status' => 'Status',
        ];
    }
	
	public function isAssignedProperty($intpropertyId)
	{
		$intCount = LocationNodes::find()
			->where(['property_type_id'=>$intpropertyId])
			->andWhere(['!=','status','Deleted'])
			->count();
		return $intCount;
	}
	
	
}
