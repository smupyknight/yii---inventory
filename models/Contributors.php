<?php

namespace app\models;

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
	public $email;
    public $contributor_name;
    public $company_name;
    public $distributed;
    public $completed;
    /**
     * @inheritdoc
     */

  public static function tableName()
  {
      return 'contributors';
  }

	public function getCompanies()
	{
		return $this->hasOne(Companies::className(), ['id' => 'company_id']);
	}

	public function getRodeusers()
	{
		return $this->hasOne(Rodeusers::className(), ['id' => 'user_id']);
	}

    ##  Define relation b/w contibutors & contributors node
    public function getContributorNodes()
    {
        return $this->hasOne(Contributornodes::className(), ['contributor_id' => 'id']);
    }

    ##  Define relation b/w contibutors & survey
    public function getSurveys()
    {
        return $this->hasOne(Surveys::className(), ['contributor_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
					[['surveys', 'contributorNodes', 'rodeusers', 'companies'], 'safe'],
					[['firstname','lastname','contact_number','contributor_type','distribution_method','company_id'],'required','on'=>'edit'],
					[['firstname','lastname','contributor_type','distribution_method','company_id'],'required','on'=>'add'],
          [['id', 'company_id', 'user_id','contact_number'], 'integer'],
		           // [['contributor_type', 'distribution_method'], 'string', 'max' => 6],
		            //[['firstname'], 'string', 'max' => 20],
		           // [['lastname'], 'string', 'max' => 18],
		      [['contact_number'], 'string', 'max' => 14,'min'=>10],
					[['alternative_contact_number'],'integer','skipOnEmpty' => true],
					[['alternative_contact_number'], 'string', 'max' => 10,'min'=>10,'skipOnEmpty' => true],
		      [['alternative_contact_number'], 'string', 'max' => 14],
		      [['address'], 'string'],
		      [['publication'], 'string', 'max' => 100],
		      [['notes'], 'string', 'max' => 1000],
		      [['quarter'], 'string', 'max' => 1000],
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
            'contact_number' => 'Contact Number',
            'alternative_contact_number' => 'Alternative Contact Number',
            'address' => 'Address',
            'company_id' => 'Company ID',
            'user_id' => 'User ID',
            'distribution_method' => 'Distribution method',
            'publication' => 'Publication',
            'disabled' => 'Disabled',
            'quarter' => 'Quarter Contributed',
            'notes' => 'Notes',
			'name'=>'Company name',
        ];
    }

	/* Get for user full name */
	public function getFullName() {
	    return $this->firstname . ' ' . $this->lastname;
	}

	## Get Contibutors Email Id
	public function getContributorDetails($intContributorId)
	{
		$data = static::find()->where(['contributors.id' => $intContributorId])->joinWith(['rodeusers'])->one();
		return $data;
	}
}
