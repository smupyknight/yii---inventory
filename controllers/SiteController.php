<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Rodeusers;
use app\models\ContactForm;


class SiteController extends Controller
{
	public $layout = 'lay-admin';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                       
                    ],
					[
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                 ],
            ],
            
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
		//return $this->render('index');
		if(Yii::$app->user->identity->role=='Contributor')
		{
			return $this->redirect(['contributor/surveys/index']);
		}
		else
		{
			//echo Yii::$app->user->isGuest;exit;
			return $this->redirect(['admin/dashboard']);
		}
    }
	
	## ---- User login Functionality  by username(login) & password-----##
    public function actionLogin()
    {
	if (!\Yii::$app->user->isGuest) {
           return $this->goHome();
        }
	$this->layout = 'lay-account';
        $model = new LoginForm();
		
		$session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$boolAdmin = $model->login();
			if($boolAdmin) {
				if (Yii::$app->user->identity->disabled == 0) {
					## unset user identity
					 Yii::$app->user->logout();
					 $session->setFlash('error', INACTIVE_USER);
					
				} else if(Yii::$app->user->identity->disabled == 2) {
					## unset user identity
					 Yii::$app->user->logout();
					 $session->setFlash('error', DELETED_USER);
				} else {
					//echo '<pre/>';print_r(Yii::$app->user);exit;
					if(Yii::$app->user->identity->role=='Contributor')
					{
						return $this->redirect(['contributor/surveys/index']);
					}
					else
					{
						//echo Yii::$app->user->isGuest;exit;
						return $this->redirect(['admin/dashboard']);
					}
				}
			} else {
				
				//assign the message for flash variable
			    $session->setFlash('error', INVALID_USER_PASS);
			}
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionForgotlink()
    {
    	$get = Yii::$app->request->get();
    	$email = $get['email'];
    	$connection = \Yii::$app->db;

    	if ($email!="" && $user = Rodeusers::find()->where('disabled != :disabled and email = :email', ['disabled'=>2, 'email'=>$email])->one() ) {
    		
    		$strRandomCode = Yii::$app->customcomponent->Generaterandompassword();
			$intId = $user->id;
			$queryResult	=	$connection	->createCommand()->update("users", ["remember_token" => $strRandomCode,"remember_token_expires_at"=>date('Y-m-d h:i:s')], ["email" => $email,"id"=>$intId])->execute();
			if($queryResult==1)
			{
				return $this->redirect(["site/reset?param1=".base64_encode($email)."&param2=".base64_encode($strRandomCode)]);
			}
			else
			{
				return $this->redirect(['site/login']);
			}

    	} else {
    		return $this->redirect(['site/login']);
    	}
    }
	
	public function actionForgotpassword()
	{
		$this->layout = 'lay-account';
		$model		  =	new Rodeusers();
		$session = Yii::$app->session;
		$connection = \Yii::$app->db;
		if(Yii::$app->request->post())
		{
			$arrPost	=	Yii::$app->request->post();
			$strEmail	=	$arrPost['Rodeusers']['email'];
			
			if($arrPost['sbt_forgot_password']=='forgot' && !empty($arrPost))
			{
				$objUserResult = Rodeusers::find()->where('disabled != :disabled and email = :email', ['disabled'=>2, 'email'=>$strEmail])->one();//2 =>deleted

				if(!empty($objUserResult) && $objUserResult)
				{
					$strRandomCode	=	Yii::$app->customcomponent->Generaterandompassword();
					$intId			=	$objUserResult->id;
					$queryResult	=	$connection	->createCommand()->update("users", ["remember_token" => $strRandomCode,"remember_token_expires_at"=>date('Y-m-d h:i:s')], ["email" => $strEmail,"id"=>$intId])->execute();
					if($queryResult==1)  
					{
						$strResetPassLink	=	Yii::$app->request->serverName.Yii::$app->request->baseUrl."/site/reset?param1=".base64_encode($strEmail)."&param2=".base64_encode($strRandomCode);
						
						/*=============Sending reset password links================*/
						$from = Yii::$app->params['adminEmail'];
						$to	=	$strEmail;
						$logo = Yii::getAlias('@app/web/images/logo-vertical.png');
						$params = ['resetLink'=>$strResetPassLink, 'logo' => $logo];

$headers = 'From: '. $from . "\r\n" .
    'Reply-To: '. $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion(). "\r\n".
    'Content-Type: text/html; charset=UTF-8\r\n';
						
$message = '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rode survey :: Forgot password</title>
    <style>
    table a{
        font-size:12px;
        font-family:Verdana, Arial, Helvetica, sans-serif;
        color:#1777b1;
        text-decoration:underline;
    }
    table a:hover{
        color:#000;
        text-decoration:none;
    }
    table strong{
        color:#000;
    }
    </style>
</head>
<body style="font-family:Verdana">
    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; border-left:1px solid #08293D; border-right:1px solid #08293D;">
					  <tr>
						<td bgcolor="#CACCCD" height="23" style="border-bottom-style:solid; border-bottom-color:#08293d; border-bottom-width:1px;">&nbsp;</td>
					  </tr>
					  <tr>
						<td align="left" height="130" style="border-bottom: 1px solid #10537C; border-top: 1px solid rgb #8BBBD8;">
                            <img src="http://rodeonlinesurveys.co.za/web/images/logo-vertical.png">
                        </td>
                      </tr>
					 
					  <tr>
						<td style="padding:20px; font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#000; line-height:21px;">
						<p>To reset your password, complete this form:</p>
							
                          <a href="http://'.$strResetPassLink.'">'.htmlentities($strResetPassLink).'</a>
                            
						 <p>This link will expire in 60 minutes.</p>
						 <p>------------------<br />
						  Thank You,<br />			
						Support Rode Survey<br />
							
								</p></td>
					  </tr>
					  <tr>
						<td valign="top" align="center"></td>
					  </tr>
					  <tr>
						<td bgcolor="#CACCCD" height="40" style="color:#ffffff; font-size:12px; font-family: Verdana, Arial, Helvetica, sans-serif; border-top-style:solid; border-top-color:#55768a; border-top-width:1px; text-align:center; line-height:40px;">
							<p>11 DeVilliers Street, Bellville, 7530</p>
							<p>Rode & Associates (Pty) Ltd</p>
							<p>+27 (0) 219462480</p>
						</td>
					  </tr>
					</table>
</body>
</html>

';

						$result	= mail($to, 'FORGOT PASSWORD', $message, $headers);
						//Yii::$app->customcomponent->sendEmail($from, $to, 'FORGOT PASSWORD', 'forgot/html', $params);
						if($result)
						{
							$session->setFlash('success_forgot',SENT_FORGOT_MAIL_SUCCESS);
						}
						/*=============Sending reset password links================*/
						
					}
				}
				else
				{
					 $session->setFlash('error',USER_NOT_FOUND);
				}
			}
		}
		return $this->render('forgot_password',['model'=>$model]);
	}
	
	public function actionReset($param1,$param2)
	{
		$this->layout = 'lay-account';
		$model		  =	new Rodeusers();
		$model->scenario			=	'reset';
		$session = Yii::$app->session;
		$connection = \Yii::$app->db;
		$strEmail		=	base64_decode($param1);
		$strCode		=	base64_decode($param2);
		$strTodayDate	=	date('Y-m-d h:i:s');
		$objUserResult = Rodeusers::find()->where('remember_token = :remember_token and email = :email', ['remember_token'=>$strCode, 'email'=>$strEmail])->one();

		if(!empty($objUserResult) && $strTodayDate<= (date('Y-m-d h:i:s', strtotime('+1 day', strtotime($objUserResult->remember_token_expires_at)))) )
		{
			if(Yii::$app->request->post())
			{
				$arrPost	=	Yii::$app->request->post();
				
					if($arrPost['sbt_reset_password']=='reset' && !empty($arrPost))
					{
						if(!empty($strEmail) && !empty($strCode))
						{
							$objUserResult = Rodeusers::find()->where('remember_token = :remember_token and email = :email', ['remember_token'=>$strCode, 'email'=>$strEmail])->one();
							if(!empty($objUserResult) && $objUserResult)
							{
								$strNewPassword	=	$arrPost['Rodeusers']['new_password'];
								$queryResult	=	$connection	->createCommand()->update("users", ["remember_token" => '','password'=>md5($strNewPassword) ], ["email" => $strEmail])->execute();
								if($queryResult==1)  
								{
									$session->setFlash('success',PASS_RESET_SUCC);
									return $this->redirect(['site/login']);
								}
							}
							else
							{
								$session->setFlash('error',SORRY_MSG);
							}
						}
						else
						{
							$session->setFlash('error',SORRY_MSG);
						}
						
					}
			 }
		}
		else
		{
			$session->setFlash('error',EXP_TIME);
			return $this->redirect(['site/forgotpassword']);
		}
			return $this->render('reset_password',['model'=>$model]);
		
	}
	

  
}
