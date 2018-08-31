<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveyTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Node Categories';

?>

<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">
                    <h2><i class="fa fa-question"></i> <?php echo Html::encode($this->title);?></h2>
                    <div class="breadcrumb-wrapper hidden-xs">
                        <span class="label">You are here:</span>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <?php echo Html::a('Dashboard', ['site/index']);?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                             <li>
                                <?php echo Html::a('Manage Surveys', ['admin/surveys/index']);?>
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
							<div class="panel panel-default panel-blog rounded shadow">
                            <div class="panel-body">
                            	<div class="row">
                                	<div class="col-md-6">
                            		<label>Survey Category :</label>
									<?php if(isset($surveyDetails->surveyCategory)) {echo $surveyDetails->surveyCategory->name;} ?>
                                    </div>
                                    <div class="col-md-6">
                            		<label>Publication :</label>
                                    <?php if(isset($surveyDetails->publication)) {echo $surveyDetails->publication;} ?>
                                    </div>
                                 </div>
                                 <div class="row">
                                	<div class="col-md-6">
                            		<label>Survey Name :</label>
                                    <?php if(isset($surveyDetails->name)) {echo $surveyDetails->name;} ?>
                                    </div>
                                    <div class="col-md-6">
                            		<label>Who can contribute :</label>
                                    <?php if(isset($surveyDetails->contributor_type)) {echo $surveyDetails->contributor_type;} ?>
                                    </div>
                                 </div>
                            </div>
                            </div>
                           <!-- Start danger color table -->
                           
                           <p>
        					<?php echo Html::a('Create Node Category', ['addnode', 'id' => $survey_template_id], ['class' => 'btn btn-success']) ?>
                            <?php echo Html::a('Questions', ['questions', 'id' => $survey_template_id], ['class' => 'btn btn-primary']) ?>
                            <?php echo Html::a('View Audit Trail', '#', ['href'=>'javascript:void(0);' ,'class' => 'btn btn-danger']) ?>
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
                         
                            <div class="table-responsive mb-20">
                            <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'property_name',
			[
				'attribute' =>'active',
				'filter' => [
					'Active' => 'Active',
					'Inactive' => 'Inactive'
				],
			],
			[
				'header' => 'Action',
				'class' => 'yii\grid\ActionColumn',
				'visible' => ($surveyDetails->surveyQuarter->distributed == 0) ? true : false ,
				'template' => ($surveyDetails->surveyQuarter->distributed == 0) ?'{edit} {delete}' : '',
				'buttons' => 
					[
						'edit' => function ($url,$model,$key) {
							
							return Html::a('<i class="fa fa-edit"></i>', ['updatenode', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
						},
						'delete' => function($url, $model, $key) {
							return Html::a('<i class="fa fa-trash"></i>','#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs confirm-delete ' ,'data-toggle'=>'modal' ,"data-placement"=>"top",   "data-id" => $model->id, "data-url" => 'deletenode']
							);
						},
					]
			 ],
        ],
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
    ]); ?>
    
</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
      