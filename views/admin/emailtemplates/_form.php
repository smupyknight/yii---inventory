<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\EmailTemplates */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('@web/bower_components/summernote/dist/summernote.min.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\CoreAsset::className(), \yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile('@web/js/blankon.form.wysiwyg.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('@web/bower_components/summernote/dist/summernote.css');
?>

 <div class="body-content">
 	 <div class="row">
      	<div class="col-md-12">

    
     <!-- Start input fields - horizontal form -->
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
                         </div>
                        <div class="pull-right">
                            <button class="btn btn-sm" data-container="body" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse">
                            <i class="fa fa-angle-up"></i></button>
                            <button class="btn btn-sm" data-container="body" data-action="remove" data-toggle="tooltip" data-placement="top" data-title="Remove">
                            <i class="fa fa-times"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.panel-heading -->
                    <div class="panel-body no-padding rounded-bottom">
                    
					<div class="pull-right template-pad"><?php echo Html::a('Email Template Place Holders','#',['href' => 'javascript:void(0)','class' => 'btn btn-danger btn-xs','data-toggle'=>'modal' ,"data-placement"=>"top", "data-target"=>".model-email-info"]);?></div>
                    <div class="clearfix"></div>
    <?php $form = ActiveForm::begin([
			//'enableClientValidation' => false
			'options' => [
				'class' => 'form-horizontal '
			],
			'fieldConfig' => [
				'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
				'labelOptions' => [ 'class' => 'col-sm-2 control-label']
			],
		]); ?>
	<div class="form-body">
        <div class="form-group">
    		<?php echo $form->field($model, 'name', ['enableAjaxValidation' => true])
					->textInput([
						'maxlength' => false,
						'placeholder' => 'Name',
						'class' => 'form-control rounded ',
				]); ?>
    	</div>
    
        <div class="form-group">
    		<?php echo $form->field($model, 'subject')
					->textInput([
						'maxlength' => false,
						'placeholder' => 'Subject',
						'class' => 'form-control rounded ',
				]); ?>
    	</div>
        
        <div class="form-group ">
        <?php echo $form->field($model, 'body', 
			[
				'template' => '{label}<div class="col-sm-8">{input}</div>{error}',
				'enableAjaxValidation' => true
			])
			->textarea(
			[
				'rows' => 2, 
				'cols' => 10, 
				'class' => 'form-control summernote'
			])->label('Body');?>
        </div>
    </div>
	<?php if(!$model->isNewRecord) { ?>
                 <div class="form-group">
                    <?php echo $form->field($model, 'status')->radioList(['Active' => 'Active', 'Inactive' =>'Inactive']); ?>
                 </div>
     <?php }?>

     <div class="form-footer">
        <div class="pull-right">
            <?php echo Html::a('Cancel', ['index'], ['class' => 'btn btn-danger mr-5']);?>
            <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']);?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

