<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Companies */

$this->title = 'Edit Company ';
?>
<!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-globe"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                      <?php echo Html::a('Dashboard', ['site/index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage company', ['index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Edit company</li>
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
                        <h3 class="panel-title">Edit company</h3>
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
                                       <label>Company name<span class="require">*</span></label>
                                        <?= $form->field($model, 'name')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label>Email<span class="require">*</span></label>
                                        <?= $form->field($model, 'email')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                                                                                          
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Phone number<span class="require">*</span></label>
                                        <?= $form->field($model, 'phone_number')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                       <label>Fax number<span class="require">*</span></label>
                                        <?= $form->field($model, 'fax_number')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Physical address<span class="require">*</span></label>
                                        <?= $form->field($model, 'physical_address')->textArea()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                       <label>Postal address<span class="require">*</span></label>
                                         <?= $form->field($model, 'postal_address')->textArea()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                       <label>Postal code<span class="require">*</span></label>
                                        <?= $form->field($model, 'postal_code')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                       <label>Contributor code<span class="require">*</span></label>
                                         <?= $form->field($model, 'contributor_code')->textInput()->label(false); ?>
                                        <span class="text-muted help-block"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                
                                
                            </div>
                        </div>
                        <div class="pull-right">
                        	<?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>
			                <?= Html::submitButton('Save changes', ['class' => 'btn btn-success' ]) ?>
                                                                  
						</div>
                         
                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->
            <!-- End input masks -->
            </div><!-- left panel-->  
        
      
      
      </div> 
    </div><!-- /.row -->
                </div>