<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Companies;
use yii\helpers\ArrayHelper;
$this->title = 'Edit Users | Rode Survey';
/* @var $this yii\web\View */
/* @var $model app\models\Rodeusers */
/* @var $form yii\widgets\ActiveForm */
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
                 <li><?php echo Html::a('Manage Contributors',['admin/users/index']);?>
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
                        <h3 class="panel-title">Edit user profile info</h3>
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
                                       <label>First name<span class="require">*</span></label>
                                        <?= $form->field($model, 'firstname')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Last name<span class="require">*</span></label>
                                        <?= $form->field($model, 'lastname')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->

                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Contact number<span class="require">*</span></label>
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
                                    <div class="col-sm-6">
                                       <label>Address:</label>
                                        <?= $form->field($model, 'address')->textarea()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">

                                       <label>Company<span class="require">*</span></label>
                                     <?php
									 	 $arrCompanies = Companies::find()->orderby('name ASC')->all();
										 $listData=ArrayHelper::map($arrCompanies,'id','name');
										// $model->comap=$id;
									  ?>

									 <?php echo $form->field($model, 'company_id')->dropDownList($listData,['prompt'=>'Select company'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->

                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Distribution method<span class="require">*</span></label>
                                       <?php
									 	 $arrCompanies = array("online"=>"online","regular mail"=>"regular mail");
									  ?>
                                        <?php echo $form->field($model, 'distribution_method')->dropDownList($arrCompanies,['prompt'=>'Select distribution method'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Publication<span class="require">*</span></label>
                                        <?php echo $form->field($model, 'publication')->dropDownList(['Rode Report' => 'Rode Report', 'Rode Retail Report' => 'Rode Retail Report', 'Both' => 'Both'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Contributor type<span class="require">*</span></label>
                                        <?php echo $form->field($model, 'contributor_type')->dropDownList(['Both' => 'Both', 'Broker' => 'Broker', 'Owner' => 'Owner'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div>


                            </div>
                            <div class="col-sm-12">
                              <div class="row">
                                  <div class="col-sm-12">
                                     <label>Notes</label>
                                      <?= $form->field($model, 'notes')->textarea()->label(false); ?>
                                      <span class="text-muted help-block"></span>
                                  </div>
                                </div>
                              <div class="row">
                                  <div class="col-sm-12">
                                     <label>Quarter Contributed</label>
                                      <?= $form->field($model, 'quarter')->textarea()->label(false); ?>
                                      <span class="text-muted help-block"></span>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                        	<?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>
			                 <?= Html::submitButton('Update', ['class' => 'btn btn-success' ]) ?>

						</div>

                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->
            <!-- End input masks -->
            </div><!-- left panel-->



      </div>
    </div><!-- /.row -->
                </div>
