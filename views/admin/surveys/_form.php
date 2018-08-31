<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SurveyTemplates */
/* @var $form yii\widgets\ActiveForm */
?>

 <div class="body-content animated fadeIn ">
 	 <div class="row">
      	<div class="col-md-12">
			<div class="left_panel">
    
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
         <div class="panel-body rounded-bottom">
		   <?php $form = ActiveForm::begin(['class' => 'form-horizontal '
               
                /*'fieldConfig' => [
                    'template' => '{label}<br/><div class="col-sm-6">{input}</div><br/>{error}',
                    'labelOptions' => ['class' => 'col-sm-2 control-label']
                ]*/
            ]); ?>
           <div class="form-group">                          
                <div class="col-sm-12">
                   <div class="row">
                      <div class="col-sm-6">
                       <label>Survey Name<span class="require">*</span></label>
						<?= $form->field($model, 'name')->textInput([
											'placeholder' => 'Survey Name',
											'class' => 'form-control rounded ajax',
											'maxlength' => true,
											//'template' => '<div class="col-xs-6">{error}</div>'
									])->label(false); ?>
                        <span class="text-muted help-block"></span>
					  </div> 
                      <div class="col-sm-6">
                       <label>Survey Category<span class="require">*</span></label>
    					<?= $form->field($model, 'survey_template_category_id')->dropDownList($arrCatList,['prompt'=>'Select Survey Category'])->label(false);  ?>
                        <span class="text-muted help-block"></span>
					 </div>
                   </div>
					<div class="row">
                      <div class="col-sm-6">
                       <label>Publication<span class="require">*</span></label>
    					<?= $form->field($model, 'publication')->dropDownList($arrPublications,['prompt'=>'Select Publication'])->label(false);  ?>
                        <span class="text-muted help-block"></span>
   					</div> 
                    <div class="col-sm-6">
                     <label>Contributor Type<span class="require">*</span></label>
    				  <?= $form->field($model, 'contributor_type')->dropDownList($arrContList ,['prompt'=>'Select Contributor Type'])->label(false);  ?>
                      <span class="text-muted help-block"></span>
   				   </div>
                 </div>
		   </div><!-- /.form-body -->
     </div><!-- /.panel-body -->
        <div class="pull-right">
            <?php echo Html::a('Cancel', Url::previous('survey'), ['class' => 'btn btn-danger mr-5']);?>
            <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']);?>
        </div>
        <div class="clearfix"></div>
                         

    <?php ActiveForm::end(); ?>
 
                </div><!-- /.panel -->
     <!--/ End input fields - horizontal form -->
</div><!-- left panel-->  
   
    	</div>
	</div>
</div>

</div>