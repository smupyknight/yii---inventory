<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Companies;
use yii\helpers\ArrayHelper;
$this->title = 'Add Contributor | Rode Survey';
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
                        <h3 class="panel-title">Add contributor</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
				 <?php $form = ActiveForm::begin(["class"=>"form-horizontal editUser"]); ?>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>First name<span class="require">*</span></label>
                                        <?= $form->field($contrimodel, 'firstname')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Last name<span class="require">*</span></label>
                                        <?= $form->field($contrimodel, 'lastname')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>User name<span class="require">*</span></label>
                                        <?= $form->field($model, 'login')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Email<span class="require">*</span></label>
                                        <?= $form->field($model, 'email')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->
                                <?php /*?><div class="row">
                                    <div class="col-sm-6">
                                       <label>Password<span class="required">*</span></label>
                                        <?= $form->field($model, 'crypted_password')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Confirm password<span class="required">*</span></label>
                                        <?= $form->field($model, 'confirm_password')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><?php */?><!--row-->

                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Contact number</label>
                                        <?= $form->field($contrimodel, 'contact_number')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Alternative contact number</label>
                                         <?= $form->field($contrimodel, 'alternative_contact_number')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->

                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Address</label>
                                        <?= $form->field($contrimodel, 'address')->textarea()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Company<span class="require">*</span></label>
                                     <?php
									 	 $arrCompanies = Companies::find()->all();
										 $listData=ArrayHelper::map($arrCompanies,'id','name');
										// $model->comap=$id;
									  ?>

									 <?php echo $form->field($contrimodel, 'company_id')->dropDownList($listData,['prompt'=>'Select company'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->

                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Distribution method<span class="require">*</span></label>
                                       <?php
									 	 $arrCompanies = array("online"=>"online", "regular mail"=>"regular mail");
									  ?>
                                        <?php echo $form->field($contrimodel, 'distribution_method')->dropDownList($arrCompanies,['prompt'=>'Select distribution method'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>

                                    <div class="col-sm-6">
                                       <label>Publication<span class="require">*</span></label>
                                        <?php echo $form->field($contrimodel, 'publication')->dropDownList(['Rode Report' => 'Rode Report', 'Rode Retail Report' => 'Rode Retail Report', 'Both' => 'Both'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div><!--row-->

                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Contributor type<span class="require">*</span></label>
                                        <?php echo $form->field($contrimodel, 'contributor_type')->dropDownList(['Both' => 'Both', 'Broker' => 'Broker', 'Owner' => 'Owner'])->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="pull-right">
			                        <?= Html::submitButton('Save', ['class' => 'btn btn-success' ]) ?>
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
