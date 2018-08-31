<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Surveys;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveyTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $strTitle.' Audit Trail';

?>

<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">
                    <h2><i class="fa fa-list"></i> <?php echo Html::encode($this->title);?></h2>
                    <div class="breadcrumb-wrapper hidden-xs">
                        <span class="label">You are here:</span>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <?php echo Html::a('Dashboard', ['admin/dashboard/index']);?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li>
                                <?php echo Html::a('Manage '.$strTitle,$strTitle == 'Surveys' ? Url::previous('survey') :Url::previous('question'));?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                             <li class="active"><?php echo Html::encode($this->title);?></li>
                        </ol>
                    </div><!-- /.breadcrumb-wrapper -->
                </div><!-- /.header-content -->
                <!--/ End page header -->

                <!-- Start body content -->
                <div class="body-content animated fadeIn">

                    <div class="row">
                        <div class="col-md-12">
							
                           <!-- Start danger color table -->
                           
                           <p>
        					<?php //echo Html::a('Create Survey', ['create'], ['class' => 'btn btn-success']) ?>
                            <?php //echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
   						  </p>
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
                            <div style="text-align:center;" class="hideOption" id="showLoader"><?php echo Html::img('@web/images/loader.gif');?></div>
                            <div class="table-responsive mb-20">
                            <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'updatedBy',
			'action',
			'version',
            [
				'attribute' => 'created_at',
				//'format' => ['date', 'php:d M,Y'],

				'value' => function($data) {
					if($data ->created_at != '') {
					return date('Y-m-d H:i:s', strtotime($data ->created_at));
					} else {
						return 'Not Set';
					}
				}
			],
            [
                 'class' => 'yii\grid\ActionColumn',
                 'header'=> 'Action',
                 'template' => '{view} ',
                 'buttons'=>[
                  'view' => function ($url,$searchModel,$key) {
                        return Html::a('<i class="fa fa-eye"></i>', '#', ['href'=>'javascript:void(0)' ,'data-toggle' => 'modal', 'title'=>'View','class'=>'btn btn-success btn-xs view-changes']);
                    },
                ]
            ]
          
        ],
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
    ]); ?>
    
</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
      