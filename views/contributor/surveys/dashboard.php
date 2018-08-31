<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Userssearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';

?>
<section id="page-content">
 <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-globe"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                 <li class="active"><?php echo Html::encode($this->title);?></li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->
     <!-- Start body content -->
   <!-- Start body content -->
                <div class="body-content animated fadeIn">

                    <div class="row">
                        <div class="col-md-6">
                           <!-- Start danger color table -->
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

                            <div class="table-responsive mb-20">
                             <h4> Completed Surveys </h4>
                            <?php echo GridView::widget([
									'dataProvider' => $dataProvider,
									'summary'=>'',
									//'filterModel' => $searchModel,
									'summary'=>'',
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],
										'surveyquarters.surveytemplates.name',
										'surveyquarters.quarter',
										'surveyquarters.deadline',
									],
									'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
								]); ?>
							</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
                        </div><!-- /.col-md-12 -->

                        <div class="col-md-6">
                           <!-- Start danger color table -->
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
                            <div class="table-responsive mb-20">
                            <h4>Uncompleted Surveys </h4>
                            <?php echo GridView::widget([
									'dataProvider' => $dataProvider1,
									//'filterModel' => $searchModel,
									'layout'=>"{summary}\n{items}\n",
									'summary'=>'',
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],
										'surveyquarters.surveytemplates.name',
										'surveyquarters.quarter',
										'surveyquarters.deadline',
									],
									'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
								]); ?>
							</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
                        </div>
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
