<?php

namespace app\components;
use Yii;
use yii\base\Component;
use  yii\web\Session;


class CustomComponent extends Component
{
	public function Generaterandompassword($length = 8)
   {
			$alphabets = range('A','Z');
			$numbers = range('0','9');
			//$additional_characters = array('_','.');
			$final_array = array_merge($alphabets,$numbers);//,$additional_characters
				 
			$password = '';
		  
			while($length--) {
			  $key = array_rand($final_array);
			  $password .= $final_array[$key];
			}
			return $password;
 	}
	
	public function sendEmail($from, $to, $subject , $view, $params = '')
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
	?>