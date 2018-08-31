<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\Surveyquarterssearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Questions';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($dataProvider->getModels());die;
?>
<!--<div class="surveyquarters-index">-->
<section id="page-content">
                <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-table"></i> Survey Questions</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('contributor/surveys/dashboard') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                	<?php echo Html::a('Manage surveys', Url::previous('cont_survey'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Manage questions</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
	<!--/ End page header -->
    
    <div class="body-content animated fadeIn">
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

     <div class="panel panel-default panel-blog rounded shadow">
                            <div class="panel-body">
                                <div class="row">
                                 <div class="col-md-6">
                                    <label>Survey Name :</label>
                                    <?php if(isset($objGetTemplateDetails->surveytemplates->name)) {echo $objGetTemplateDetails->surveytemplates->name;} ?>
                                    </div>
                                    <div class="col-md-6">
                                    <label>Who can contribute :</label>
                                    <?php if(isset($objGetTemplateDetails->surveytemplates->contributor_type)) {echo $objGetTemplateDetails->surveytemplates->contributor_type;} ?>
                                    </div>
                                 </div>
                                 <div class="row">
                                     <div class="col-md-6">
                                    <label>Quarter :</label>
                                    <?php if(isset($objGetTemplateDetails)) {echo$objGetTemplateDetails->quarter;} ?>
                                    </div>
                                    <div class="col-md-6">
                                    <label>Publication :</label>
                                    <?php if(isset($objGetTemplateDetails->surveytemplates->publication)) {echo $objGetTemplateDetails->surveytemplates->publication;} ?>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                    <label>Deadline:</label>
                                    <?php if(isset($objGetTemplateDetails->deadline)) {echo $objGetTemplateDetails->deadline;} ?>
                                    </div>
                                    <div class="col-md-6">
                                    <label>Status :</label>
                                    <?php if(isset($objGetTemplateDetails->closed) && $objGetTemplateDetails->closed == '1') {echo 'Closed';} else {echo 'Open';} ?>
                                    </div>
                                 </div>
                            </div>
             </div>
           <?php //comment: $objGetTemplateDetails->deadline > date('Y-m-d H:i:s') && 
           if($objGetTemplateDetails->closed != '1' && $ansCount > 0 && $surveyDetail['completed'] != 1 && !isset($_GET["demo"])) {
                echo Html::a('Complete Survey', '#', ['href'=>'javascript:void(0)','class' => 'btn btn-success mr-5 confirm-completion', 'data-id' => $surveyDetail['id'] ,'data-toggle'=>'modal' ,"data-cont-id"=>$intContributorId]);
            }
            if(isset($_GET["demo"])) {
                echo Html::a('Complete Survey', '#', ['href'=>'#','id' => 'demoComplete','class' => 'btn btn-success mr-5']);
            }
            if($objGetTemplateDetails->closed == '1') {
                echo '<div class="alert alert-warning" role="alert"><i class="fa fa-lock" aria-hidden="true"></i> Survey locked.</div>';
            }
            if($surveyDetail['completed'] == 1) {
                echo '<div class="alert alert-warning" role="alert"><i class="fa fa-check-square" aria-hidden="true"></i> Survey completed.</div>';
            }
            if($ansCount == 0) {
                echo '<div class="alert alert-warning" role="alert"><i class="fa fa-table" aria-hidden="true"></i> No answers in survey.</div>';
            }
           ?>
               
<div class="table-responsive mb-20">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		//'dataProvider' => new yii\data\ActiveDataProvider(['query' => $searchModel->getSurveytemplatequestions()]),
       // 'filterModel' => $searchModel,
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			/*[
				'format'=>'html',
				'attribute'=>'question',
			],*/
			[
				'attribute'=>'question',
				'format' => 'raw',
				'value' => function($data) {
				 return '<span  data-content="'.strip_tags($data->question).'" data-trigger="hover" data-placement="top" data-toggle="popover" >'.substr(strip_tags($data->question), 0, 100).'</span>';
				},
		   ],
            [
				'header'=>'Actions',
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}',
				'buttons' => [
								'view' => function ($url,$searchModel,$key) use($SurveyQuarterId) {
											//echo '<pre>';
											//print_r($searchModel);die;
											return Html::a('<i class="fa fa-pencil"></i>', ['contributor/questions/answer', 'id'=>$searchModel->id,'quarter_id'=>$SurveyQuarterId], ['data-toggle' => 'modal','class'=>'btn btn-success btn-xs']);
							},
					],
			],
        ],
    ]); ?>
 </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
 <?php if(!empty($commentModel)) { ?>                          
 <div class="row">
        <div class="col-md-12">
            <!-- Start input fields - horizontal form -->
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
                          <strong>Comment</strong>
                         </div>
                          <div class="clearfix"></div>
                    </div>
                    <div class="panel-body no-padding rounded-bottom">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                        'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-8">{input}</div>{error}',
                            'labelOptions' => ['class' => 'col-sm-2 control-label']
                        ]
                    ]); ?>
                        <div class="form-body">
                                <div class="form-group">
                                    <?php echo $form->field($commentModel, 'body')->textArea([
                                            'maxlength' => true,
                                            'rows' => 5,
                                            'class' => 'form-control',
                                            'cols' =>  10
                                           
                                    ])->label(false);?>
                                
                                   
                        </div><!-- /.form-body -->
                        
                        <div class="form-footer">
                                <div class="pull-right">
                                    <?php //echo Html::a('Remove', ['index'], ['class' => 'btn btn-danger mr-5']);?>
                                    <?php echo Html::submitButton('Update', ['class' => 'btn btn-success']);?>
                                </div>
                                <div class="clearfix"></div>
                         </div><!-- /.form-footer -->
                       <?php ActiveForm::end();?>
                    </div><!-- /.panel-body -->
                </div>
        </div>
</div>
                            
                        </div><!-- /.col-md-12 -->
                        <?php }?>
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
<!--</div>-->
