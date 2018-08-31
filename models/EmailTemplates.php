<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_templates".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property string $created_at
 * @property string $updated_at
 */
class EmailTemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['subject'], 'string', 'max' => 50],
            //[['body'], 'string', 'max' => 2518],
			[['id', 'type', 'name', 'subject', 'body', 'status'], 'trim'],
			[['name', 'subject', 'body'], 'required', 'on' => ['create', 'update']],
			['name', 'match' ,'pattern'=>'/^[A-Za-z-& ]+$/u','message'=> INVALID_EMAIL_NAME, 'on'=>['create', 'update']],
			['body', 'validateEditor', 'on' => ['create', 'update'], 'when' => function($model) {
        		return $model->body == '<p><br></p>';}]
			
			
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'subject' => 'Subject',
            'body' => 'Body',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	## Custom validation for editor
	public function validateEditor($attribute, $params)
	{
		 $this->addError($attribute, BODY_BLANK_ERR);
	}
}
