<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$fieldOptions1 = [
    'options' => ['class' => 'input-group input-group-lg rounded no-overflow input-group-signin'],
];

$this->title = 'FORGOT PASSWORD | Rode Survey';

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
    <?php $form = ActiveForm::begin(['id' => 'forgot-password-form', 'class' => 'sign-in form-horizontal shadow rounded no-overflow', 'fieldConfig' => [
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
                <?php echo $form->field($model, 'email', $fieldOptions1)->label(false)->textInput(['placeholder' => 'Email','class' => 'form-control input-sm email',]); ?>
            </div>
        </div>
        <div class="sign-footer">
            <div class="form-group">
            	<?= Html::submitButton('Send reset email', ['class'=> 'btn btn-theme btn-lg btn-block no-margin rounded','name'=>'sbt_forgot_password','value'=>'forgot']) ;?>
            </div>
        </div>
    <?php ActiveForm::end();?>
    <!--/ Lost password form -->

    <!-- Content text -->
    <p class="text-muted text-center sign-link">Back to <?php echo Html::a('Log in', ['site/login'], ['title' => 'Sign in']);?></p>
    <!--/ Content text -->

</div>