<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "temp_notification_mail".
 *
 * @property integer $id
 * @property integer $contributor_id
 * @property integer $survey_id
 */
class TempNotificationMail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp_notification_mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contributor_id', 'survey_id'], 'required'],
            [['contributor_id', 'survey_id'], 'integer'],
        ];
    }



    public static function primaryKey()
    {
        return 'id';
        // For composite primary key, return an array like the following
        // return array('pk1', 'pk2');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contributor_id' => 'Contributor ID',
            'survey_id' => 'Survey ID',
        ];
    }

	public function getContributorList()
	{
		$arrData = static::find()->asArray()->limit(50)->all();
		return $arrData;
	}

	public static function sendEmail($from, $to, $subject , $view, $params = '')
	{
		$params['logo'] = Yii::getAlias('@app/web/images/logo-vertical.png');
		return  Yii::$app->mailer->compose( $view, ['params' => $params])
                ->setFrom($from)
                ->setTo($to)
				->setReplyTo($from)
                ->setSubject($subject)
                ->send();
	}
}
