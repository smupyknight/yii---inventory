<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\widgets\Survey;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EmailTemplates */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('@web/bower_components/summernote/dist/summernote.min.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\CoreAsset::className(), \yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile('@web/js/blankon.form.wysiwyg.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('@web/bower_components/summernote/dist/summernote.css');

$this->title = isset($model->id) ? 'Update Question' : 'Create Question';
?>
<section id="page-content">


    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-question"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                      <?php echo Html::a('Dashboard', ['admin/dashboard/index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Surveys',Url::previous('survey'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Questions', Url::previous('question'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Create</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->
 <div class="body-content">
 	 <div class="row">
      	<div class="col-md-12">
         <?php echo $surveyDetails=(Survey::widget(['id' => $intSurveyId, 'type' => 'Survey']));?>
    
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
                    <div class="clearfix"></div>
    <?php $form = ActiveForm::begin([
			//'enableClientValidation' => false
			'options' => [
				'class' => 'form-horizontal ',
				//'enctype'=>'multipart/form-data'
			],
			'fieldConfig' => [
				'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
				'labelOptions' => [ 'class' => 'col-sm-2 control-label']
			],
		]); ?>
	<div class="form-body">
        <div class="form-group">
    		<?php echo $form->field($model, 'property_type_id')
					->dropDownList($propertyList ,[
						'prompt' => 'Select Property Type ',
						'class' => 'form-control rounded ',
				]); ?>
    	</div>
    
       <div class="form-group ">
        <?php echo $form->field($model, 'question', 
			[
				'template' => '{label}<div class="col-sm-8">{input}</div>{error}',
				'enableAjaxValidation' => true
			])
			->textarea(
			[
				'rows' => 2, 
				'cols' => 10, 
				'class' => 'form-control summernote',
				//'id' => 'question'
			])->label('Question');?>
        </div>
        
        <div class="form-group ">
        <?php echo $form->field($model, 'information', 
			[
				'template' => '{label}<div class="col-sm-8">{input}</div>{error}',
				//'enableAjaxValidation' => true
			])
			->textarea(
			[
				'rows' => 2, 
				'cols' => 10, 
				'class' => 'form-control summernote'
			])->label('Information');?>
        </div>
    </div>
	

     <div class="form-footer">
        <div class="pull-right">
            <?php echo Html::a('Cancel', Url::previous('question'), ['class' => 'btn btn-danger mr-5']);?>
            <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']);?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
