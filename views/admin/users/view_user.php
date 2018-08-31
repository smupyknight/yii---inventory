<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = 'View User';
?>
<section id="page-content">
<div class="header-content">
        <h2><i class="fa fa-table"></i> User info</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                	<i class="fa fa-users"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/users/index') ?>">Manage users</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li class="active">User info</li>
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
                        <h3 class="panel-title">View user info <strong><?php if(isset($model->contributors->fullName)) { echo $model->contributors->fullName; } else { echo '-'; } ?></strong></h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                        <div class="form-group">                          
                            <div class="col-sm-12">
                                <div class="row">
                                	<div class="col-sm-6">
                                       <label><strong>Login name :</strong></label><br/>
                                        <?php if(isset($model->login)) { echo $model->login; } else { echo '_'; } ?>
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
                                           <label><strong>Name :</strong></label><br/>
                                           <?php if(isset($model->contributors->fullName)) { echo $model->contributors->fullName; } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label><strong>Company :</strong> </label><br/>
                                       <?php if(isset($model->contributors->companies->name)) { echo $model->contributors->companies->name; } else { echo '-'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                           <label><strong>Contact number :</strong></label><br/>
                                           <?php if(isset($model->contributors->contact_number)) { echo $model->contributors->contact_number; } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label><strong>Alternate contact number :</strong> </label><br/>
                                       <?php if(isset($model->contributors->alternative_contact_number)) { echo $model->contributors->alternative_contact_number; } else { echo '-'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-12">
                                           <label><strong>Address :</strong></label><br/>
                                           <?php if(isset($model->contributors->address)) { echo nl2br($model->contributors->address); } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                           <label><strong>Distribution method :</strong></label><br/>
                                           <?php if(isset($model->contributors->distribution_method)) { echo $model->contributors->distribution_method; } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>
                                    <div class="col-sm-6">
                                       <label><strong>	publication :</strong> </label><br/>
                                       <?php if(isset($model->contributors->publication)) { echo $model->contributors->publication; } else { echo '-'; } ?>
                                        <span class="text-muted help-block error-email required"></span>
                                    </div>                                                                      
                                </div><!--row-->
                                <div class="row">
                                    <div class="col-sm-6">
                                           <label><strong>Contributor type :</strong></label><br/>
                                           <?php if(isset($model->contributors->contributor_type)) { echo $model->contributors->contributor_type; } else { echo '-'; } ?>
                                            <span class="text-muted help-block error-email required"></span>
                                    </div>  
                                    <div class="col-sm-6">
                                           <label><strong>Status :</strong></label><br/>
                                           <?php
										    if(isset($model->disabled))
											{ 
												if($model->disabled=='0')
												{ 
													echo 'Inactive'; 
												}
												elseif($model->disabled=='1')
												{
													echo 'Active';
												}
												elseif($model->disabled=='2')
												{
													echo 'Deleted';
												}
											}	
											else
											{
												echo '-';
											}
											?>
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
