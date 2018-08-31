<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchLocationNodes */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Child Nodes';
$this->params['breadcrumbs'][] = $this->title;

## Add CSS & JS for this page
$this->registerJsFile('@web/bower_components/smoothness/jquery-ui.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\CoreAsset::className()]]);
$this->registerJsFile('@web/js/pages/smoothness.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\CoreAsset::className()]]);
$this->registerCssFile('@web/bower_components/smoothness/jquery-ui.css');
?>
 
<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">
                    <h2><i class="fa fa-map-marker"></i> <?php echo Html::encode($this->title);?></h2>
                    <div class="breadcrumb-wrapper hidden-xs">
                        <span class="label">You are here:</span>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <?php echo Html::a('Dashboard', ['admin/dashboard/index']);?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li>
                               <?php echo Html::a('Manage Parent Nodes', ['admin/nodes/index']);?>
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
                           <div class="row">
                           		<div class="col-md-6">
                                   <strong>Property Type :</strong><?php echo $propertyName;?>
                            	</div>
                                <div class="col-md-6">
                                   <strong>Parent Node :</strong><?php echo $parentName;?>
                            	</div>
                           		
                           </div>
                           <div class="clearfix"></div>
                           <p>
        					          <?php echo Html::a('Create Child Node', ['createchild', 'prop' => $propertyId, 'parent' => $parentId], ['class' => 'btn btn-success', 'data-pjax' => '0']) ?>
                            <?php echo Html::a('Reset', ['childnodes', 'parent' => $parentId], ['class' => 'btn btn-default']) ?>
   						             </p>
                          <div class="alert alert-danger hideOption error">
               				 <span><i class="icon fa fa-ban"></i> </span>
                             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            	<span id="error"></span>
                            </div>
                            <div class="alert alert-success hideOption success">
               				 <span><i class="icon fa fa-check"></i> </span>
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <span id="success"></span>
                            </div>
                            
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
                            
                            <?php Pjax::begin(['id'=>'nodes-grid']); ?>
                            <?php echo Html::input('hidden', 'page',(isset($_GET['page'])) ? $_GET['page'] : 0,['id' => 'currPage']) ?>
                            <?php echo Html::input('hidden', 'per_page',(isset($_GET['per-page'])) ? $_GET['per-page'] : 0,['id' => 'per_page']) ?>
                                <?= GridView::widget([
        							'dataProvider' => $dataProvider,
        							'filterModel' => $searchModel,
        							'columns' => [
            							//['class' => 'yii\grid\SerialColumn'],
											'name',
											'description',
           									 'code',
											[
												'attribute'=>'status',
			 									'filter'=>
													[
														'Active' => 'Active', 
														'Inactive' => 'Inactive', 
														'Deleted' => 'Deleted'
													],
			 									'content'=>function($data){
													return ucfirst($data->status);}
											],
											/*[
												'header' => 'Position',
												'class' => 'yii\grid\ActionColumn',
												'template' =>'{up} {down}',
												'buttons' => 
													[
														'up' => function ($url,$model,$key) {
															if($model->position > 1) {
									return Html::a('<i class="fa fa-arrow-up"></i>', ['admin/users/view', 'id'=>$key], ['title'=>'View','class'=>'btn btn-success btn-xs']);
															}
           												},
														 'down' => function ($url,$model,$key) {
	               					return Html::a('<i class="fa fa-arrow-down"></i>', ['admin/users/update', 'id'=>$key], ['data-toggle' => 'modal', 'title'=>'Update','class'=>'btn btn-primary btn-xs']);
														}
													]
											],*/
											[
												'header'=>'position',
			 									'content'=>function($data){
													return ucfirst($data->position);}
											],
											
											[
												'header' => 'Action',
												'class' => 'yii\grid\ActionColumn',
												'template' =>'{edit} {delete}',
												'buttons' => 
													[
														'edit' => function ($url,$model,$key) {
														return Html::a('<i class="fa fa-pencil"></i>', ['updatechild', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
														},
														 'delete' => function ($url,$model,$key) {
															 $class = "";
															if($model->status=='Deleted'){
																$class = "hideOption";
															}
													$isAssignedtoChild = $model->checkIsAssigned($model->id);
													if($isAssignedtoChild == 0) {
	               					return Html::a('<i class="fa fa-trash"></i>','#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs confirm-delete '.$class ,'data-toggle'=>'modal' ,"data-placement"=>"top","data-id" => $model->id, 'data-pjax' => '0'])
									.Html::a('<i class="fa fa-plus"></i>', ['createchild', 'prop' =>  $model->property_type_id, 'parent' => $model->id], ['class' => 'btn btn-success btn-xs '.$class ,'data-toggle'=>'tooltip' ,"data-placement"=>"top", 'data-pjax' => '0']);
													} else {
									return Html::a('<i class="fa fa-trash"></i>','#', [
                    'href'=>'javascript:void(0)' ,
                    'class' => 'btn btn-danger btn-xs '.$class ,
                    'data-toggle'=>'modal' ,
                    "data-placement"=>"top", 
                    "data-target"=>".modal-cant-del"])
                  .Html::a('<i class="fa fa-eye"></i>', 
                    ['childnodes', 'parent' => $model->id], 
                    [
                    'class' => 'btn btn-success btn-xs ' ,
                    'data-toggle'=>'tooltip' ,
                    "data-placement"=>"top"]);				
													}
														},
													]
											],
											
										
        						],
					'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
					'rowOptions'=>function ($model, $key, $index, $grid){
							if($model->status=='Deleted'){
								 return ['class'=>'deleted position', 'id'=>'position_'.$model->id];
							} else {
								 return ['class'=>'position', 'id'=>'position_'.$model->id];
							}
							
						}
    				]); ?> <?php Pjax::end(); ?>
                            </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
							<p>
                    <?php echo Html::a('Create Child Node', ['createchild', 'prop' => $propertyId, 'parent' => $parentId], ['class' => 'btn btn-success', 'data-pjax' => '0']) ?>
                    <?php echo Html::a('Reset', ['childnodes', 'parent' => $parentId], ['class' => 'btn btn-default']) ?>
              </p>
                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
<script>
    
</script>