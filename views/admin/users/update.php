<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'Edit Users ';
?>

 <div class="header-content">
        <h2><i class="fa fa-users"></i> Contributor</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li><?php echo Html::a('Manage Contributors',['index']);?>
                 <i class="fa fa-angle-right"></i></li>
                 <li class="active">Update Contributor</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->
<div class="body-content animated fadeIn user-edit">
    <div class="row">

        <div class="col-md-12">
        	<div class="left_panel">
            <!-- Start input masks -->
            
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Edit username</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                <?php $form = ActiveForm::begin(["class"=>"form-horizontal editUser", "id" => "change-password"]); ?>
                <?php
                    $session = Yii::$app->session;
                    $successu    = $session->hasFlash('successu');
                    if(!empty($successu)) {
                   ?>
                    <div class="alert alert-success">
                        <span><i class="icon fa fa-check"></i> </span>
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <span>
                        <?php echo $session->getFlash('successu'); ?> </span>
                    </div>
                    <?php } ?>
                <?php
                    $erroru    = $session->hasFlash('erroru');
                    if(!empty($erroru)) {
                   ?>
                    <div class="alert alert-danger">
                        <span><i class="icon fa fa-times"></i> </span>
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <span>
                        <?php echo $session->getFlash('erroru'); ?> </span>
                    </div>
                    <?php } ?>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Username<span class="require">*</span></label>
                                        <?= $form->field($model, 'login')->textInput()->label(false); ?>
                                        <span class="text-muted help-block error-login required"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>
                            <?= Html::submitButton('Change Username', ['class' => 'btn btn-success','value' => 'change-login' , 'name' =>'change-login' ]) ?>

                        </div>

                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->

            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Edit user info</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
				<?php $form = ActiveForm::begin(["class"=>"form-horizontal editUser", "id" => "change-password"]); ?>
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
                <?php
                    $error    = $session->hasFlash('error');
                    if(!empty($error)) {
                   ?>
                    <div class="alert alert-danger">
                        <span><i class="icon fa fa-times"></i> </span>
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <span>
                        <?php echo $session->getFlash('error'); ?> </span>
                    </div>
                    <?php } ?>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Email<span class="require">*</span></label>
                                        <?= $form->field($model, 'email')->textInput()->label(false); ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-sm-6">
                                       <label>Status<span class="require">*</span></label>
                                       <?php if(!$model->isNewRecord) { ?>
                                    		<div class="form-group">
                                       			 <?php echo $form->field($model, 'disabled')->radioList(['1' => 'Active', '0' =>'Inactive', '2' => 'Deleted'])->label(false); ?>
                                    		</div>
                                	 <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                        	<?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>
			                <?= Html::submitButton('Change Email', ['class' => 'btn btn-success','value' => 'change-email' , 'name' =>'change-email' ]) ?>

						</div>

                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->




            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Change password</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
				 <?php $form = ActiveForm::begin(["class"=>"form-horizontal editUser","id" => "change-password"]); ?>
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
                            	<!--<div class="row">
                                    <div class="col-sm-6">
                                       <label>Current password<span class="require">*</span></label>
                                         <? $form->field($model, 'password')->passwordInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>New password<span class="require">*</span></label>
                                        <?= $form->field($newModel, 'new_password')->passwordInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-sm-6">
                                       <label>Confirm password<span class="require">*</span></label>
                                         <?= $form->field($newModel, 'confirm_password')->passwordInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                  </div><!--row-->

                            </div>

                        </div>
                        <div class="pull-right">
                         	<?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>
			                <?= Html::submitButton('Change Password', ['class' => 'btn btn-success' ,'value' => 'change-password' , 'name' =>'change-password' ]) ?>

						</div>

                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div>
            <!-- End input masks -->
            </div><!-- left panel-->



      </div>
    </div><!-- /.row -->
                </div>
