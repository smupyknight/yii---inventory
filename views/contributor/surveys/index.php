<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\contributor\Surveys;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Surveyssearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Surveys';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($searchModel->contributors);die;
?>
<section id="page-content">
                <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-table"></i> Surveys</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('contributor/surveys/dashboard') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li class="active">Manage surveys</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
	<!--/ End page header -->

    
    
	<!-- Start body content -->
    <div class="body-content animated fadeIn">
    <div class="panel panel-default panel-blog rounded shadow">
                            <div class="panel-body">
                                 <div class="row">
                                	<div class="col-md-6">
                            		<label>Contributor Name :</label>
                                    <?php if(isset($searchModel->contributors->firstname) && isset($searchModel->contributors->lastname)) {echo $searchModel->contributors->firstname .' '.$searchModel->contributors->lastname;} ?>
                                    </div>
                                    <div class="col-md-6">
                            		<label>Distribution Method :</label>
                                    <?php if(isset($searchModel->contributors->distribution_method)) {echo $searchModel->contributors->distribution_method;} ?>
                                    </div>
                                 </div>
                            </div>
                            </div>
    <?php	if(Yii::$app->session->hasFlash('success')){ ?>
                <div class="alert alert-success alert-dismissable">
                 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <span>
                        <i class="icon fa fa-check"></i> 
                        <?php echo Yii::$app->session->getFlash('success'); ?>
                    </span>
                </div>
	<?php } ?>
    <div class="row">
        <div class="col-md-12">

          <div class="panel rounded shadow">
                    <div class="panel-heading">
                          <span><strong><i class="fa fa-envelope"></i> : Not yet Answered </strong></span> &nbsp;
                          <span><strong><i class="fa fa-mail-forward"></i> : Completed </strong></span>&nbsp;
                          <span><strong><i class="fa fa-envelope-o"></i> : Open </strong></span>&nbsp;
                          <span><strong><i class="fa fa-question btn btn-primary btn-xs"></i> : Survey Questions </strong></span>&nbsp;
                          
                    </div>
                </div>  
        	<?php echo $this->render('_search', [
								'model' => $searchModel, 
								'quarters' => $quarters,
								]);?>
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
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute'=>'survey_name',
				//'value'=>'surveyquarters.surveytemplates.name',
				'format'=>'raw',
				'value' => function($data,$key)
				{
                 	return Html::a($data['surveyquarters']['surveytemplates']->name, ['contributor/questions/index','id'=>$data->survey_quarter_id], ['title' => 'Questions']); 
             	 }
			],
			[
				'attribute'=>'quarter',
				'value'=>'surveyquarters.quarter',
				'filter'=>false,
			],
			
			[
				'attribute'=>'deadline',
				//'header'=>'Deadline',
				'value'=>'surveyquarters.deadline',
				'format' => ['date', 'php:d M  Y'],
			],
           
			[
             	'attribute'=>'completed',
                'filter'=>false,
                'format' => 'raw',
				        'value'=> function($data) { 
                   return Surveys::isNewSurvey($data->id, $data->completed);
                },
			],

             [
                'header' => 'Action',
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{question} ',
                'buttons' => 
                    [
                        'question' => function ($url,$model,$key) {
                            return Html::a('<i class="fa fa-question"></i>', ['contributor/questions/index','id'=>$model->survey_quarter_id], 
                                ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
                        },
                    ]
             ],
            
        ],
    ]); ?>
    
     </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->

<?php Url::remember(Url::to(),'cont_survey');?>

