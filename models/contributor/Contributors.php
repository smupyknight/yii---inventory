<?php

namespace app\models\contributor;

use Yii;

/**
 * This is the model class for table "contributors".
 *
 * @property integer $id
 * @property string $contributor_type
 * @property string $firstname
 * @property string $lastname
 * @property string $contact_number
 * @property string $alternative_contact_number
 * @property string $address
 * @property integer $company_id
 * @property integer $user_id
 * @property string $distribution_method
 * @property string $publication
 * @property string $disabled
 */
class Contributors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	 
    public static function tableName()
    {
        return 'contributors';
    }
	
	/*public function getCompanies()
	{
		return $this->hasOne(Companies::className(), ['id' => 'company_id']);
	}*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['firstname','lastname','contact_number'],'required','on'=>'edit'],//,'contributor_type','distribution_method','company_id'
            [['id', 'company_id', 'user_id','contact_number'], 'integer'],
            [['contributor_type', 'distribution_method'], 'string', 'max' => 6],
            [['firstname'], 'string', 'max' => 20],
            [['lastname'], 'string', 'max' => 18],
            [['contact_number'], 'string', 'max' => 10,'min'=>10],
			[['alternative_contact_number'],'integer','skipOnEmpty' => true],
			[['alternative_contact_number'], 'string', 'max' => 10,'min'=>10,'skipOnEmpty' => true],
            [['alternative_contact_number'], 'string', 'max' => 14],
            [['address'], 'string'],
            [['publication'], 'string', 'max' => 150],
            [['disabled'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contributor_type' => 'Contributor type',
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'contact_number' => 'Contact number',
            'alternative_contact_number' => 'Alternative contact number',
            'address' => 'Address',
            'company_id' => 'Company',
            'user_id' => 'User ID',
            'distribution_method' => 'Distribution method',
            'publication' => 'Publication',
            'disabled' => 'Disabled',
			'name'=>'Company name',
        ];
    }
	
	/* Get for user full name */
		public function getFullName() {
		    return $this->firstname . ' ' . $this->lastname;
		}
}
