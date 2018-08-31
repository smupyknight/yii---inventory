<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rode survey :: Forgot password</title>
    <style>
    table a{
        font-size:12px;
        font-family:Arial, Helvetica, sans-serif;
        color:#1777b1;
        text-decoration:underline;
    }
    table a:hover{
        color:#FFFFFF;
        text-decoration:none;
    }
    table strong{
        color:#000;
    }
    </style>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; border-left:1px solid #08293D;
								border-right:1px solid #08293D;">
					  <tr>
						<td bgcolor="#CACCCD" height="23" style="border-bottom-style:solid; border-bottom-color:#08293d; border-bottom-width:1px;">&nbsp;</td>
					  </tr>
					  <tr>
						<td align="left" height="130" style="border-bottom: 1px solid #10537C; border-top: 1px solid rgb #8BBBD8;">
                            <?php echo Html::a(Html::img($message->embed($params['logo'])), [Yii::$app->request->serverName.Yii::$app->request->baseUrl]);?>
                        </td>
                      </tr>
					 
					  <tr>
						<td style="padding:20px; font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#000; line-height:21px;">
						Dear User,
						<br />
                        You have requested to reset password.
						<p>Please click below link to reset your password.</p>
							
                          <a href="<?php echo $message->embed($params['resetLink']); ?>"><?php echo $message->embed($params['resetLink']); ?></a>
                            
						 <p>------------------<br />
						  Thank You,<br />			
						Support Rode Survey<br />
							
								</p></td>
					  </tr>
					  <tr>
						<td valign="top" align="center"></td>
					  </tr>
					  <tr>
						<td bgcolor="#CACCCD" height="40" style="color:#ffffff; font-size:12px; font-family: Arial, Helvetica, sans-serif; border-top-style:solid; border-top-color:#55768a; border-top-width:1px; text-align:center; line-height:40px;">&copy; <?php echo date('Y'); ?> <a href="http://surveys.rode.co.za" style="color:#FFFFFF">Rode Survey</a>.
						  All Rights Reserved.</td>
					  </tr>
					</table>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
