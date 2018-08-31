<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */

//Yii::$app->clientScript->registerCoreScript('jquery.ui');
$this->title = 'Login | Rode Survey';

$fieldOptions1 = [
    'options' => ['class' => 'input-group input-group-lg rounded no-overflow input-group-signin'],
    'inputTemplate' => "{input}",
	
];

$fieldOptions2 = [
    'options' => ['class' => 'input-group input-group-lg rounded no-overflow input-group-signin'],
    'inputTemplate' => "{input}"
];

?>

<div id="sign-wrapper">

    <!-- Brand -->
    <div class="brand">
        <img src="<?= \Yii::getAlias('@web') ?>/images/logo-vertical.png" alt="brand logo"/>
    </div>
    <!--/ Brand -->

    <!-- Login form -->
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'sign-in form-horizontal shadow rounded no-overflow', 'fieldConfig' => [
            'template' => "{input}\n{error}",
            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],]); ?>
    
        <div class="sign-header">
            <div class="form-group">
                <div class="sign-text">
                    <span>Login</span>
                </div>
            </div><!-- /.form-group -->
        </div><!-- /.sign-header -->
        <div class="sign-body">
        <?php if(Yii::$app->session->hasFlash('error')) : ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <span><i class="icon fa fa-ban"></i></span>
                    <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif; ?>
            <div class="form-group">
                <?php echo $form
            		->field($model, 'login', $fieldOptions1)
            		->label(false)
            		->textInput([
						'placeholder' => 'Username or e-mail address',
						'class' => 'form-control input-sm user',
						//'template' => '<div class="col-xs-6">{error}</div>'
				]); ?>
                    
               
            </div><!-- /.form-group -->
            <div class="form-group">
            	<?php echo $form
					->field($model, 'crypted_password', $fieldOptions2)
					->label(false)
					->passwordInput([
						'placeholder' => 'Password',
						'class' => 'form-control input-sm lock'
					]);?>
               
            </div><!-- /.form-group -->
        </div><!-- /.sign-body -->
        <div class="sign-footer">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="ckbox ckbox-theme">
                        
                          <input id="rememberMe" name="LoginForm[rememberMe]" type="checkbox" value="1" checked="checked">
                            <label for="rememberMe" class="rounded">Remember me</label>
                            <?php //echo $form->field($model, 'rememberMe')->checkbox(array(
								 //'label'=>'',
									// 'labelOptions'=>array('class'=>'rounded','style'=> 'margin-left:2%', 'checked' => 'checked'))); ?>
                                     
                           
                        </div>
                    </div>
                    <div class="col-xs-6 text-right">
                        <?php echo Html::a('Forgot password?', ['site/forgotpassword'], ['title' => 'Forgot Password']);?>
                    </div>
                </div>
            </div><!-- /.form-group -->
            <div class="form-group">
                <?php echo Html::submitButton('Login', ['class' => 'btn btn-theme btn-lg btn-block no-margin rounded', 'name' => 'login-btn', 'id'=>'login-btn']);?>
            </div><!-- /.form-group -->
        </div><!-- /.sign-footer -->
    <?php ActiveForm::end();?><!-- /.form-horizontal -->
    <!--/ Login form -->

   

</div><!-- /#sign-wrapper -->