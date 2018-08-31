<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $login
 * @property string $name
 * @property string $email
 * @property string $crypted_password
 * @property string $salt
 * @property string $created_at
 * @property string $updated_at
 * @property string $remember_token
 * @property string $remember_token_expires_at
 * @property string $disabled
 * @property string $confirmation
 */
class Rodeusers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $new_password;
	public $confirm_password;
	public $old_password;

    public static function tableName()
    {
        return 'users';
    }

    public function getContributors()
  	{
  		return $this->hasOne(Contributors::className(), ['user_id' => 'id']);
  	}


    public function getContributorStatus(){
		    $types = array('0'=> 'Inactive', '1' => 'Active', '2' => 'Deleted');
		    return isset($types[ $this->disabled ]) ? $types[ $this->disabled ] : 'not set';
		}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			['email','required'],//
			[['login'],'required','on'=>'add'],//,'crypted_password','confirm_password'				remove this two comments when password functionality begins
			//['confirm_password', 'compare', 'compareAttribute' => 'crypted_password','on'=>'add'],
			['login', 'match', 'pattern' => '/^[a-z0-9]\w*$/i','on'=>'add','message'=>'Login should contain a-zA-Z0-9.'],
			[['email'],'email'],
			[['email'],'unique'],
			[['new_password','confirm_password'],'required','on'=>'reset'],
			['confirm_password', 'compare', 'compareAttribute' => 'new_password','on'=>'reset'],
      [['id'], 'integer'],
      [['login'], 'string', 'max' => 18],
      [['name'], 'string', 'max' => 60],
      [['email'], 'string', 'max' => 42],
      [['crypted_password', 'salt'], 'string', 'max' => 40],
      //[['created_at', 'updated_at'], 'string', 'max' => 19],
      //[['remember_token', 'remember_token_expires_at'], 'string', 'max' => 10],
      [['disabled'], 'string', 'max' => 1],
      [['confirmation'], 'string', 'max' => 36],
      [['login', 'email'], 'required', 'on' => ['profile']],
      [['login', 'email'], 'unique', 'on' => ['profile']],
      [['new_password', 'confirm_password', 'old_password'], 'required', 'on' => ['changepassword']],
      [['new_password', 'confirm_password'], 'required', 'on' => ['updatepassword']],
      ['new_password','string', 'min'=>8, 'message' =>MIN_PASSWORD_LEN,'on' => ['changepassword','reset','updatepassword']],
      ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => INVALID_CONFIRM_PASSWORD, 'on' => ['changepassword', 'resetpassword','updatepassword']],
      ['old_password', 'validatePassword', 'on' => ['changepassword']],
      [['login'],'required','on'=>'changelogin'],
         ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'name' => 'Name',
            'email' => 'Email',
            'crypted_password' => 'Password',
            'salt' => 'Salt',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remember_token' => 'Remember Token',
            'remember_token_expires_at' => 'Remember Token Expires At',
            'disabled' => 'Disabled',
            'confirmation' => 'Confirmation',
      			'new_password'=>'New password',
      			'confirm_password'=>'Confirm password',
      			'fullName'=>'Name',
      			'distribution_method'=>'Distribution method',
      			'company_id'=>'Company id',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (md5($this->$attribute) != Yii::$app->user->identity->password) {
                    $this->addError($attribute, INCORRECT_PASSWORD);
                }
    }


}
