<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "companies".
 *
 * @property integer $id
 * @property string $name
 * @property string $physical_address
 * @property string $postal_address
 * @property integer $postal_code
 * @property string $phone_number
 * @property string $fax_number
 * @property string $email
 * @property string $contributor_code
 * @property integer $display_code
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['name','physical_address','postal_address','postal_code','phone_number','fax_number','email','contributor_code'],'required'],
			['email','email'],
			['email','unique'],
			[['phone_number'], 'string','min'=>10,'max'=>10],
			[['phone_number','postal_code'],'integer'],
            [['id', 'postal_code', 'display_code'], 'integer'],
            [['name'], 'string', 'max' => 61],
            [['physical_address'], 'string', 'max' => 84],
            [['postal_address'], 'string', 'max' => 78],
            //[['phone_number'], 'string', 'max' => 13],
            [['fax_number'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 42],
            [['contributor_code'], 'string', 'max' => 9],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Company name',
            'physical_address' => 'Address',
            'postal_address' => 'Postal address',
            'postal_code' => 'Postal code',
            'phone_number' => 'Phone',
            'fax_number' => 'Fax',
            'email' => 'Email',
            'contributor_code' => 'Contributor code',
            'display_code' => 'Display Code',
        ];
    }
}
