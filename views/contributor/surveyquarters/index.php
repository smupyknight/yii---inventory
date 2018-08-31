<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
        <h2><i class="fa fa-table"></i> Survey Questions of</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('contributor/surveys/dashboard') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                	<a href="<?= Yii::$app->getUrlManager()->createUrl('contributor/surveys/index') ?>">Manage surveys </a>
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

   <?php /*?> <h1><?= Html::encode($this->title) ?></h1><?php */?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <?php /*?> <p>
        <?= Html::a('Create Surveyquarters', ['create'], ['class' => 'btn btn-success']) ?>
    </p><?php */?>
	<div class="table-responsive mb-20">
    <?= GridView::widget([
        //'dataProvider' => $dataProvider,
		'dataProvider' => new yii\data\ActiveDataProvider(['query' => $searchModel->getSurveytemplatequestions()]),
        'filterModel' => $searchModel,
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
            //'id',
            //'survey_template_id',
            //'distributed',
            //'quarter',
            //'closed',
            // 'deadline',
            // 'created_at',
            // 'updated_at',
            // 'distributable',

            [
				'header'=>'Actions',
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}',
				'buttons' => [
								'view' => function ($url,$searchModel,$key) use($SurveyQuarterId) {
											//echo '<pre>';
											//print_r($searchModel);die;
											return Html::a('<i class="fa fa-eye"></i>', ['contributor/questions/view', 'id'=>$key,'surveyquarterid'=>$SurveyQuarterId], ['data-toggle' => 'modal', 'title'=>'View','class'=>'btn btn-success btn-xs']);
							},
					],
			],
        ],
    ]); ?>
 </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
<!--</div>-->
