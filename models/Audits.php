<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audits".
 *
 * @property integer $id
 * @property integer $auditable_id
 * @property string $auditable_type
 * @property integer $user_id
 * @property string $user_type
 * @property string $username
 * @property string $action
 * @property string $changes
 * @property integer $version
 * @property string $created_at
 */
class Audits extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auditable_id', 'user_id', 'version'], 'integer'],
            [['changes'], 'string'],
            [['created_at'], 'safe'],
            [['auditable_type', 'user_type', 'username', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auditable_id' => 'Auditable ID',
            'auditable_type' => 'Auditable Type',
            'user_id' => 'User ID',
            'user_type' => 'User Type',
            'username' => 'Username',
            'action' => 'Action',
            'changes' => 'Changes',
            'version' => 'Version',
            'created_at' => 'Created At',
        ];
    }
    ## GEt the name of user who updated survey
    public function getUpdatedBy()
    {
       $nameArr = User::find()->select('login')
       ->where(['id' => $this->user_id])
       ->asArray()
       ->One();
       if(!empty($nameArr)) {
         return $nameArr['login'];
       } else {
        return '';
       }
    }
}
