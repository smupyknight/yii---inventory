<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Companies;
use yii\helpers\ArrayHelper;
$this->title = 'Edir Contributors | Rode Survey';
/* @var $this yii\web\View */
/* @var $model app\models\Rodeusers */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="body-content animated fadeIn user-edit">
    <div class="row">
		
        <div class="col-md-12">
        	<div class="left_panel">
            <!-- Start input masks -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Edit Contributor profile info</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
				 <?php $form = ActiveForm::begin(["class"=>"form-horizontal editUser"]); ?>
                 <?php
				 	$session = Yii::$app->session;
				 	$success	= $session->hasFlash('success');
                    if(!empty($success)) {
                   ?>
                    <div class="alert alert-success">
                    	<span><i class="icon fa fa-check"></i> </span>
                    	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <span>
                        <?php echo $session->getFlash('success'); ?> </span>
                    </div>
                    <?php } ?>

                        <div class="form-group">                          
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>First name<span class="required">*</span></label>
                                        <?= $form->field($model, 'firstname')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                       <label>Last name<span class="required">*</span></label>
                                        <?= $form->field($model, 'lastname')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Contact number<span class="required">*</span></label>
                                        <?= $form->field($model, 'contact_number')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                       <label>Alternative contact number:</label>
                                         <?= $form->field($model, 'alternative_contact_number')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                
                                <div class="row">
                                    <div class="col-sm-12">
                                       <label>Address:</label>
                                        <?= $form->field($model, 'address')->textarea(['rows'=>8])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                       
                                </div><!--row--> 
                            </div>
                        </div>
                        <div class="pull-right">
			                        <?= Html::submitButton('Save changes', ['class' => 'btn btn-success' ]) ?>
                                    <?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>                              
						</div>
                         
                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->
            <!-- End input masks -->
            </div><!-- left panel-->  
      </div> 
    </div><!-- /.row -->
                </div>