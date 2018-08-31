<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\widgets\Survey;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OutputTablesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Output Tables';
?>
<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">
                    <h2><i class="fa fa-globe"></i> <?php echo Html::encode($this->title);?>

                    </h2>
                    <div class="breadcrumb-wrapper hidden-xs">
                        <span class="label">You are here:</span>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <?php echo Html::a('Dashboard', ['admin/dashboard/index']);?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li>
                                <?php echo Html::a('Manage Surveys',Url::previous('survey'));?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li>
                                <?php echo Html::a('List of Output Tables',['outputtables', 'id' => $survey_id , 'quarter' => $currentQuarter ]);?>
                    <i class="fa fa-angle-right"></i>
                            </li>

                            <li>
                                <?php echo Html::a('Calculation', ['calculations' ,'id' => $filterArray['output_table_id'], 'quarter' => $currentQuarter, 'qid' => $quarter_id]);?>
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
                         <?= GridView::widget([
                          'id' => 'w0',
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
           'company_name',
           'value',
            [
                'header' => 'Action',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{exclude} ',
                'buttons' =>
                    [
                        'exclude' => function($url, $model, $key) {
                            if($model->included == 1) {
                                $string = "<i class='fa fa-close'>&nbsp; Exclude</i>";
                             }   else {
                                $string = "<i class='fa fa-check'>&nbsp; Include</i>";
                             }
                            return Html::a($string,'#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-success btn-xs exclude-answer', "data-id" => $model->id , "data-included" => $model->included]
                            );

                        },
                    ]
             ],

        ],
        'tableOptions' =>['class' => 'table table-striped table-bordered table-success', 'id' => 'output-tablegrid'],
    ]); ?>

</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->



                        </div><!-- /.col-md-12 -->
                         <?php echo Html::endForm();?>
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
<?php Url::remember(Url::to(),'outputtable');?>
