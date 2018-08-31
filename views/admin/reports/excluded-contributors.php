<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
//use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchPropertyTypes */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Excluded Contributors';
//$this->registerJsFile('@web/js/all-krajee.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">
                    <h2><i class="fa fa-globe"></i> <?php echo Html::encode($this->title);?></h2>
                    <div class="breadcrumb-wrapper hidden-xs">
                        <span class="label">You are here:</span>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <?php echo Html::a('Dashboard', ['admin/dashboard/index']);?>
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
                            <?php echo Html::a('Erratic Contributors', ['erraticcontributors'], ['class' => 'btn btn-warning']) ?>
        					<?php echo Html::a('Inactive Contributors', ['inactivecontributors'], ['class' => 'btn btn-danger']) ?>
                            <?php echo Html::a('Reset', ['excludedcontributors'], ['class' => 'btn btn-default']) ?>
   						  </p>
                           <?php if(Yii::$app->session->hasFlash('error')) : ?>
            				<div class="alert alert-danger alert-dismissable">
               				 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            	<h4>
                                	<i class="icon fa fa-ban"></i> 
                                	<?php echo Yii::$app->session->getFlash('error'); ?>
                            	</h4>
                        	</div>
                            
                    	<?php elseif(Yii::$app->session->hasFlash('success')) : ?>
            				<div class="alert alert-success alert-dismissable">
               				 <span><i class="icon fa fa-check"></i> </span>
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <span> <?php echo Yii::$app->session->getFlash('success'); ?></span>
                            	
                        	</div>
                    	<?php endif; ?>
                         
                            <div class="table-responsive mb-20">
                            <?php $gridColumns = [
                            ['class' => 'yii\grid\SerialColumn'],
                                            'contributor_name',
                                            [
                                              'attribute'=>  'name',
                                              'label' => 'Survey',
                                            ],
                                            'quarter',
                                            [
                                              'attribute'=>'survey_template_question_id',
                                              'label' => 'Question',
                                            ],
                                            [
                                              'attribute'=>  'node',
                                              'label' => 'node',
                                            ],
                                            'value',
                                            'exclude_reason'
                            ];?>
                                <?php echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'fontAwesome' => true,
    'dropdownOptions' => [
        'label' => 'Export All',
        'class' => 'btn btn-default'
    ],
        'exportConfig' => [
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_PDF => false
    ]
]) . "<hr>\n";
                                echo  \kartik\grid\GridView::widget([
        							'dataProvider' => $dataProvider,
        							'filterModel' => $searchModel,
                                    //'pjax' => true,
                                    //'export' => true,

        							'columns' => $gridColumns,
                      'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
					
    				]); ?>
                            </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
                             <p>
                             <?php echo Html::a('Erratic Contributors', ['erraticcontributors'], ['class' => 'btn btn-warning']) ?>
                             <?php echo Html::a('Inactive Contributors', ['inactivecontributors'], ['class' => 'btn btn-danger']) ?>
                            <?php echo Html::a('Reset', ['excludedcontributors'], ['class' => 'btn btn-default']) ?>
                          </p>
                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
      