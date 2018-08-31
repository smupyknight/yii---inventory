<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Companies */
$this->title 	=	'Company View';
//$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<section id="page-content">
<div class="header-content">
        <h2><i class="fa fa-table"></i> Company info</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/companies/index') ?>">Manage company</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li class="active">Company info</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
</div>
<div class="body-content animated fadeIn">
    <div class="row">
		
        <div class="col-md-12">
        	
            <!-- Start input masks -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">View Company info <strong><?php if(isset($model->name)) { echo $model->name; } else { echo '-'; } ?></strong></h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                        <div class="form-group">                          
                            <div class="col-sm-12">
                                <div class="row">
                                	<div class="col-sm-6">
                                       <label><strong>Company name :</strong></label><br/>
                                        <?php if(isset($model->name)) { echo $model->name; } else { echo '_'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label><strong>Email :</strong> </label><br/>
                                        <?php if(isset($model->email)) { echo $model->email; } else { echo '-'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                           <label><strong>Phone number :</strong></label><br/>
                                           <?php if(isset($model->phone_number)) { echo $model->phone_number; } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label><strong>Fax number :</strong> </label><br/>
                                       <?php if(isset($model->fax_number)) { echo $model->fax_number; } else { echo '-'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-12">
                                           <label><strong>Physical address :</strong></label><br/>
                                           <?php if(isset($model->physical_address	)) { echo nl2br($model->physical_address); } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>  
                                     <div class="col-sm-12">
                                           <label><strong>Postal address :</strong></label><br/>
                                           <?php if(isset($model->postal_address)) { echo nl2br($model->postal_address); } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                         
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                           <label><strong>Postal code :</strong></label><br/>
                                           <?php if(isset($model->postal_code)) { echo $model->postal_code; } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label><strong>Contributor code :</strong> </label><br/>
                                       <?php if(isset($model->contributor_code)) { echo $model->contributor_code; } else { echo '-'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                            </div>
                        </div>
                        
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->
            <!-- End input masks -->
             
      </div> 
    </div><!-- /.row -->
                </div>
