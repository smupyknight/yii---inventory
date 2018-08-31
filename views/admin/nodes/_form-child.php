<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyTypes */
/* @var $form yii\widgets\ActiveForm */
?>

 <div class="body-content animated fadeIn">
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
                    <?php $form = ActiveForm::begin([
						'options' => [
							'class' => 'form-horizontal'
						],
						'fieldConfig' => [
							'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
							'labelOptions' => ['class' => 'col-sm-2 control-label']
						]
					]); ?>
                        <div class="form-body">
                        <?php echo $form->field($model, 'property_type_id')->hiddenInput()->label(false);?>
                        <?php echo $form->field($model, 'location_node_id')->hiddenInput()->label(false);?>
                        		<div class="form-group">
                                    <?php echo $form->field($model, 'propertyName')->textInput([
											'class' => 'form-control rounded',
											'maxlength' => true,
											'readonly'=>true
									]);?>
                                </div>
                                
                                <div class="form-group">
                                    <?php echo $form->field($model, 'parentName')->textInput([
											'class' => 'form-control rounded',
											'maxlength' => true,
											'readonly'=>true
									]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($model, 'name', ['enableAjaxValidation' => 'true'])->textInput([
											'placeholder' => 'Name',
											'class' => 'form-control rounded ajax',
											'maxlength' => true,
									]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($model, 'code')->textInput([
											'placeholder' => 'Code',
											'class' => 'form-control rounded',
											'maxlength' => true,
									]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($model, 'description')->textArea([
											'placeholder' => 'Description',
											'class' => 'form-control rounded',
											'maxlength' => true,
											'rows' => 5,
											'style' => 'resize:none;'
									]);?>
                                </div>
                                  <?php if(!$model->isNewRecord) { ?>
                                     <div class="form-group">
                                        <?php echo $form->field($model, 'status')->radioList(['Active' => 'Active', 'Inactive' =>'Inactive']); ?>
                                     </div>
                                 <?php }?>
                                   
						</div><!-- /.form-body -->
                        
                        <div class="form-footer">
                                <div class="pull-right">
                                    <?php echo Html::a('Cancel', ['childnodes', 'parent' => $model->location_node_id], ['class' => 'btn btn-danger mr-5']);?>
                                    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']);?>
                                </div>
                                <div class="clearfix"></div>
                         </div><!-- /.form-footer -->
                       <?php ActiveForm::end();?>
                    </div><!-- /.panel-body -->
                </div><!-- /.panel -->
     <!--/ End input fields - horizontal form -->

   
    	</div>
	</div>
</div>

