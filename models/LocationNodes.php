<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "location_nodes".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $property_type_id
 * @property string $location_node_id
 * @property string $created_at
 * @property integer $position
 * @property string $description
 */
class LocationNodes extends \yii\db\ActiveRecord
{
	 const STATUS_VAL = 'Active';
	 public $propertyName ;
	 public $maxPostion;
	 public $parentName;
    /**
     * @inheritdoc
     */
	public static function tableName()
    {
        return 'location_nodes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_type_id', 'position'], 'integer'],
            [['name'], 'string', 'max' => 121],
            [['code'], 'string', 'max' => 5],
            [['location_node_id'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 255],
			[['name', 'code', 'description', 'location_node_id', 'property_type_id', 'status'], 'trim'],
			['name', 'required', 'on' => ['create','update', 'createChild', 'updateChild']],
			['name', 'uniqueParent', 'on' => ['create','update']],
			['name', 'match' ,'pattern'=>'/^[A-Za-z0-9-:_().&\/\' ]+$/u','message'=> INVALID_NODE_NAME, 
			'on'=>['create', 'update', 'createChild', 'updateChild']],
			['name', 'uniqueChild', 'on' => ['createChild', 'updateChild']],
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
            'code' => 'Code',
            'property_type_id' => 'Property Type',
            'location_node_id' => 'Location Node ID',
            'created_at' => 'Created At',
            'position' => 'Position',
            'description' => 'Description',
        ];
    }
	
	## Define Relation with Property Types
	public function getProperty()
	{
		return $this->hasOne(PropertyTypes::className(), ['id' => 'property_type_id']);
	}
	
	## Function to get list of property types
	public function getPropertyTypes()
	{
		$arrPropTypes = PropertyTypes::find()->select('id, name')
			->where(['status' => self::STATUS_VAL])
			->orderBy('name')
			->asArray()
			->all();
		return $arrPropTypes;
	}
	
	## Function to Change sequence of nodes
	public static function setChangeSequence($arrPositions, $intCount)
	{
		$connection = Yii::$app->db;
        if (is_array($arrPositions)) {
            foreach ($arrPositions as $posId) {
                $connection ->createCommand()
					->update('location_nodes', ['position' => $intCount ], 'id ='.$posId)
					->execute();
                $intCount++;
         	}
			return json_encode(['status' => 'success', 'message' => POSITION_CHANGED]);
		} else {
			return json_encode(['status' => 'error', 'message' => POSITION_CHANGE_ERR]);
		}
	}
	
	## function to get Property Type name 
	public function getPropertyTypeName($intPropId)
	{
		$arrPropTypename = PropertyTypes::find()->select('name')
		->where(['id' => $intPropId])
		->asArray()
		->One();
		return $arrPropTypename['name'];
	}
	
	## Function for getting max postion of specific property for new record
	public function getcurrentPosition($intPropId, $intLocId = '')
	{
		if($intLocId=="") {
			$arrPosition = static::find()->select('max(position)maxPostion')
			/*->where([
				'property_type_id' => $intPropId, 
				'location_node_id'=> ''
			])*/
			->where('location_node_id IS NULL')
			->orWhere(['location_node_id' => ''])
			->andWhere([
				'property_type_id' => $intPropId, 
				])
			->asArray()
			->One();
		} else {
			$arrPosition = static::find()->select('max(position)maxPostion')
			->where([
				'property_type_id' => $intPropId,
				'location_node_id'=> $intLocId
			])
			->asArray()
			->One();
		}
		return ($arrPosition['maxPostion'] + 1);
	}
	
	## Function to check if node is assigned to any child node, if yes,do not delete
	public function checkIsAssigned($intNodeId)
	{
		$intCount = static::find()
			->where(['location_node_id' => $intNodeId])
			->andWhere(['!=','status','DELETED'])
			->count();
		return $intCount;
	}
	
	## function to get Parent name 
	public function getParentName($intParentId)
	{
		$arrParentname = static::find()->select('name')
			->where(['id' => $intParentId])
			->asArray()
			->One();
		return $arrParentname['name'];
	}
	
	## Function to check unique child under each parent
	public function uniqueChild($attribute, $params)
	{
		if($this->id == "") {
			$intCount = static::find()->where([
				'location_node_id'=>$this->location_node_id, 
				'name'=>trim($this->$attribute)])
				->count();
		} else {
			$intCount = static::find()->where([
				'location_node_id'=>$this->location_node_id, 
				'name'=>trim($this->$attribute)])
				->andWhere(
				['!=','id',$this->id])
				->count();
		}
		if($intCount > 0) {
			return $this->addError($attribute, 'Name "'.$this->$attribute.'" has already been taken.');
		}
	}
	
	## Function to check unique parent under each property id
	public function uniqueParent($attribute, $params)
	{
		if($this->id == "") {
		    $intCount = static::find()
					/*->where([
					 'property_type_id'=>$this->property_type_id,
					 'name'=>trim($this->$attribute), 
					 'location_node_id' => ''])*/
					 ->where('location_node_id IS NULL')
					 ->orWhere(['location_node_id' => ''])
					 ->andWhere([
					 	'property_type_id'=>$this->property_type_id,
					 	'name'=>trim($this->$attribute), 
					 ])
					 ->count();
		} else {
			/*$intCount = static::find()->where([
					 'property_type_id'=>$this->property_type_id,
					 'name'=>trim($this->$attribute),
					 'location_node_id' => ''])
					 ->andWhere(
					 ['!=','id',$this->id])
					 ->count();*/
			$intCount = static::find()
					 ->where('location_node_id IS NULL')
					 ->orWhere(['location_node_id' => ''])
					 ->andWhere([
					 	'property_type_id'=>$this->property_type_id,
					 	'name'=>trim($this->$attribute), 
					 ])
					 ->andWhere(
					 ['!=','id',$this->id])
					 ->count();
					 
		}
		if($intCount > 0) {
			return $this->addError($attribute, 'Name "'.$this->$attribute.'" has already been taken.');
		}
	}
}
