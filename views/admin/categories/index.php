<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchTemplateCategories */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Template Categories';
?>

<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">
                    <h2><i class="fa fa-cubes"></i> <?php echo Html::encode($this->title);?></h2>
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
        					<?php echo Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
                            <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
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
                         
                            <div class="table-responsive mb-20">
                                <?= GridView::widget([
        							'dataProvider' => $dataProvider,
        							'filterModel' => $searchModel,
        							'columns' => [
            							['class' => 'yii\grid\SerialColumn'],
											'name',
											['attribute'=>'status',
			 								'filter'=>array('Active' => 'Active', 'Inactive' => 'Inactive', 'Deleted' => 'Deleted'),
			 									'content'=>function($data){
												return ucfirst($data->status);}],
											[
												'label' => 'Action'  ,       
    											'content' => function ($model, $key, $index, $column) {
													$class = "";
													if($model->status=='Deleted'){
														$class = "hideOption";
													}
													return Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"])
													. Html::a('<i class="fa fa-trash"></i>','#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs confirm-delete '.$class ,'data-toggle'=>'modal' ,"data-placement"=>"top",  "data-id" => $model->id]
													);
				
    									}
								],
										//['class' => 'yii\grid\ActionColumn'],
										
        						],
					'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
					'rowOptions'=>function ($model, $key, $index, $grid){
						if($model->status=='Deleted'){
							 return ['class'=>'deleted'];
						}}
    				]); ?>
                            </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

                            <p>
                            <?php echo Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
                            <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                          </p>
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
      
