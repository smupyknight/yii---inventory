<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$fieldOptions1 = [
    'options' => ['class' => 'input-group input-group-lg rounded no-overflow input-group-signin'],
];

$this->title = 'RESET PASSWORD | Rode Survey';

$session = Yii::$app->session;
?>
<div id="sign-wrapper">

	<!-- Brand -->
    <div class="brand">
        <img src="<?= \Yii::getAlias('@web') ?>/images/logo-vertical.png" alt="brand logo"/>
    </div>	
	<!--/ Brand -->
<?php $result 	= $session->hasFlash('error');
	  $success	= $session->hasFlash('success_forgot');
		if(!empty($result)) {
	   ?>
		<div class="alert alert-danger">
			<button class="close" data-close="alert"></button>
			<span>
			<?php echo $session->getFlash('error'); ?> </span>
		</div>
        <?php } 
        if(!empty($success)) {
	   ?>
		<div class="alert alert-success">
			<button class="close" data-close="alert"></button>
			<span>
			<?php echo $session->getFlash('success_forgot'); ?> </span>
		</div>
        <?php } ?>
    <!-- Lost password form -->
    <?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'class' => 'sign-in form-horizontal shadow rounded no-overflow', 'fieldConfig' => [
            'template' => "{input}\n{error}"]]); ?>
     
		
		
        <div class="sign-header">
            <div class="form-group">
                <div class="sign-text">
                    <span>Reset your password</span>
                </div>
            </div>
        </div>
        <div class="sign-body">
            <div class="form-group">
                <?php echo $form->field($model, 'new_password', $fieldOptions1)->label(false)->passwordInput(['placeholder' => 'New password','class' => 'form-control input-sm lock',]); ?>
            </div>
        </div>
        <div class="sign-body">
            <div class="form-group">
                <?php echo $form->field($model, 'confirm_password', $fieldOptions1)->label(false)->passwordInput(['placeholder' => 'Confirm new password','class' => 'form-control input-sm lock',]); ?>
            </div>
        </div>
        <div class="sign-footer">
            <div class="form-group">
            	<?= Html::submitButton('Reset password',['class'=> 'btn btn-theme btn-lg btn-block no-margin rounded','name'=>'sbt_reset_password','value'=>'reset']) ;?>
            </div>
        </div>
    <?php ActiveForm::end();?>
    <!--/ Lost password form -->

    <!-- Content text -->
    <!--<p class="text-muted text-center sign-link">Back to <a href="page-signin.html"> Sign in</a></p>-->
    <!--/ Content text -->

</div>