<?php

namespace app\models;

use Yii;
use app\models\PropertyTypes;
use app\models\LocationNodes;
/**
 * This is the model class for table "contributor_nodes".
 *
 * @property integer $id
 * @property string $name
 * @property integer $location_node_id
 * @property integer $contributor_id
 * @property integer $property_type_id
 */
class Contributornodes extends \yii\db\ActiveRecord
{
    public $property;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contributor_nodes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location_node_id', 'contributor_id', 'property_type_id'], 'integer'],
            [['name'], 'string', 'max' => 102],
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
            'location_node_id' => 'Location Node ID',
            'contributor_id' => 'Contributor ID',
            'property_type_id' => 'Property Type ID',
        ];
    }

    ## Define Relation between Nodes & Contributor
    public function getNodes()
    { 
        return $this->hasOne(LocationNodes::ClassName(), ['id' => 'location_node_id' ]);
    }

    ## Define Relation between Property type & Contributor nodes
    public function getPropertytypes()
    {
        return $this->hasOne(PropertyTypes::ClassName(), ['id' => 'property_type_id']);
    }
}
