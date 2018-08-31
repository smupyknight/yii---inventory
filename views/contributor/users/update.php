<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Edir Users | Rode Survey';
?>
<div class="body-content animated fadeIn user-edit">
    <div class="row">
		
        <div class="col-md-12">
        	<div class="left_panel">
            <!-- Start input masks -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Edit User Details</h3>
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
                                       <label>Email<span class="required require">*</span></label>
                                        <?= $form->field($model, 'email')->textInput()->label(false); ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                            </div>
                        </div>
                        <div class="pull-right">
			                        <?= Html::submitButton('change email', ['class' => 'btn btn-success' ]) ?>
                                    <?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>                              
						</div>
                         
                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->
            
            
            
            
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Change Password</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
				 <?php $form = ActiveForm::begin(["class"=>"form-horizontal editUser","action"=>"admin/users/changepassword"]); ?>
				<?php
				 	/*$session = Yii::$app->session;
				 	$success	= $session->hasFlash('success');
                    if(!empty($success)) {*/
                   ?>
                    <!--<div class="alert alert-success">
	                    <span><i class="icon fa fa-check"></i> </span>
                    	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <span>
                        <?php //echo $session->getFlash('success'); ?> </span>
                    </div>-->
                    <?php //} ?>
                        <div class="form-group">                          
                            <div class="col-sm-12">
                            	<div class="row"> 
                                    <div class="col-sm-6">
                                       <label>Current password<span class="required require">*</span></label>
                                         <?= $form->field($model, 'old_password', ['enableAjaxValidation' => 'true'])
                                         ->passwordInput([
                                            'placeholder' => 'Old Password',
                                            'class' => 'form-control rounded ajax',
                                            'maxlength' => true,
                                    ])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>New password<span class="required require">*</span></label>
                                        <?= $form->field($model, 'new_password')->passwordInput(
                                        [
                                            'placeholder' => 'New Password',
                                            'class' => 'form-control rounded',
                                            'maxlength' => true,
                                        ])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                  </div> 
                                  <div class="row"> 
                                    <div class="col-sm-6">
                                       <label>Confirm password<span class="required require">*</span></label>
                                         <?= $form->field($model, 'confirm_password')->passwordInput([
                                            'placeholder' => 'Confirm Password',
                                            'class' => 'form-control rounded',
                                            'maxlength' => true,
                                    ]
                                         )->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                
                            </div>
                        </div>
                        <div class="pull-right">
			                        <?= Html::submitButton('Change password', ['class' => 'btn btn-success' ]) ?>
                                    <?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>                              
						</div>
                         
                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div>
            <!-- End input masks -->
            </div><!-- left panel-->  
        
      
      
      </div> 
    </div><!-- /.row -->
                </div>
