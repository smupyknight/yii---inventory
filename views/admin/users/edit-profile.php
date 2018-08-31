<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyTypes */

$this->title = 'Edit Profile';
?>
<section id="page-content">


    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-user"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                      <?php echo Html::a('Dashboard', ['site/index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active"><?php echo Html::encode($this->title);?></li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <div class="body-content animated fadeIn">
 	 <div class="row">
      	<div class="col-md-12">

    		 <?php if(Yii::$app->session->hasFlash('error')) : ?>
            				<div class="alert alert-danger">
               				 <span><i class="icon fa fa-ban"></i> </span>
                             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            	<span><?php echo Yii::$app->session->getFlash('error'); ?></span>
                            </div>
                            
                    	<?php elseif(Yii::$app->session->hasFlash('success')) : ?>
            				<div class="alert alert-success alert-dismissable">
               				 <span><i class="icon fa fa-check"></i> </span>
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <span> <?php echo Yii::$app->session->getFlash('success'); ?></span>
                            	
                        	</div>
                    	<?php endif; ?>
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
							'class' => 'form-horizontal '
						],
						'fieldConfig' => [
							'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
							'labelOptions' => ['class' => 'col-sm-2 control-label']
						]
					]); ?>
                        <div class="form-body">
                                <div class="form-group">
                                    <?php echo $form->field($model, 'name')->textInput([
											'placeholder' => 'Name',
											'class' => 'form-control rounded ajax',
											'maxlength' => true,
									]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($model, 'login', ['enableAjaxValidation' => 'true'])->textInput([
											'placeholder' => 'Login',
											'class' => 'form-control rounded ajax',
											'maxlength' => true,
									]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($model, 'email', ['enableAjaxValidation' => 'true'])->textInput([
											'placeholder' => 'Email',
											'class' => 'form-control rounded ajax',
											'maxlength' => true,
									]);?>
                                </div>
                                  
                                  
						</div><!-- /.form-body -->
                        
                        <div class="form-footer">
                                <div class="pull-right">
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



