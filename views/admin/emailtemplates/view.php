<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EmailTemplates */

$this->title = $model->name;
?>

<section id="page-content">


    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-envelope"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                      <?php echo Html::a('Dashboard', ['site/index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Email Templates', ['index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">View</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <!-- Start body content -->
         <div class="body-content animated fadeIn">

                    <div class="row" id="blog-single">
                        <div class="col-md-12">

                           <!-- Start danger color table -->
                           
                           <p>
        					<?php //echo Html::a('Create Email Template', ['create'], ['class' => 'btn btn-success']) ?>
                            <?php //echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
   						  </p>
                           
                         
                            <div class="panel panel-default panel-blog rounded shadow">
                    		<div class="panel-body">
                                <?php echo DetailView::widget([
									'model' => $model,
									'attributes' => [
										//'id',
										//'type',
										'name',
										'subject',
										[
											'attribute'=>'body',
    										'format'=>'raw',
  										],	
										'created_at',
										'updated_at',
									],
								]) ?></div>
                            </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
    <!--/ End body content -->
      