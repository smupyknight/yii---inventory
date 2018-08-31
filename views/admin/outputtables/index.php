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
                    <?php if($objQuarterDetails['distributed'] == 1 ) {?>
                    <span class="badge badge-teals badge-stroke">Distributed</span>
                    <?php } if($objQuarterDetails['closed'] == 1) {?>
                    <span class="badge badge-lilac badge-stroke">Closed</span>
                    <?php }?>
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
                                <?php echo Html::a('Manage Questions', Url::previous('question'));?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                
                             <li class="active"><?php echo Html::encode($this->title);?></li>
                        </ol>
                    </div><!-- /.breadcrumb-wrapper -->
                </div><!-- /.header-content -->
                <!--/ End page header -->
<?php  $actionFlag = 0;
if($objQuarterDetails['distributed'] != 1 && $objQuarterDetails['closed'] != 1 && ($objQuarterDetails['deadline'] == '' || $objQuarterDetails['deadline'] >= date('Y-m-d')))  {
  $actionFlag = 1;
  }
  ?>
                <!-- Start body content -->
                <div class="body-content animated fadeIn">

                    <div class="row">
                        <div class="col-md-12">
                        <?php echo $surveyDetails=(Survey::widget(['id' => $intQuestionId, 'type' => 'Question']));?>
                        
                           <!-- Start danger color table -->
                           <!-- Start danger color table -->
                           <?php echo Html::beginForm(Url::to(['admin/outputtables/deleteall', 'id' =>$intQuestionId]), 'post',['id'=>'delete-all-form']) ;?>
                           <p>

                            <?php if($actionFlag == 1) {echo Html::a('Create Output Table', ['admin/outputtables/create', 'id' =>$intQuestionId ], ['class' => 'btn btn-success']);} ?>
                            <?php echo Html::a('Reset ', ['admin/outputtables/index','questid' => $intQuestionId, 'quart' => $objQuarterDetails['quarter'] ], ['class' => 'btn btn-default']);?>
                            
             <?php if($actionFlag == 1) {echo Html::submitButton('Delete All', ['class' => 'btn btn-danger del-btn', 'style'=>" float:right;", 'disabled'=>true]) ; }?>
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
                         <?= GridView::widget([
                          'id' => 'w0',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn',
             'visible' => ($actionFlag == 1) ? true : false,
             'checkboxOptions' => function ($model, $key, $index, $column) {
    return ['value' => $model->id, 'class' => 'chkcontributor'];
}],
            'heading',
            'sub_heading',
            [
                'attribute'=>'output_column',
                'filter'=>
                    [
                        'No Column' => 'No Column', 
                        'n' => 'n', 
                        'Difference' => 'Difference'
                    ],
                'content'=>function($data){
                    return ($data->output_column == 'Difference') ? $data->output_column.'('.$data->difference_presentation.')' : $data->output_column;
                }
             ],
             'parent_node_visibility',
             [
              'attribute'=>'sd',
               'value' => function($data) {
                 return ($data->sd!="") ? $data->sd : '';
                 
               }
               ],
            [
                'header' => 'Action',
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{edit} {delete} {view}',
                'buttons' => 
                    [
                        'edit' => function ($url,$model,$key) {
                            return Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
                        },
                        'delete' => function ($url,$model,$key) {
                                return Html::a('<i class="fa fa-trash"></i>','#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs confirm-delete ' ,'data-toggle'=>'modal' ,"data-placement"=>"top",  "data-id" => $model->id]);
                         },
                        'view' => function ($url,$model,$key) {
                            return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn btn-success btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
                        },
                                                    ]
                                            ],
        ],
        'tableOptions' =>['class' => 'table table-striped table-bordered table-success', 'id' => 'output-tablegrid'],
    ]); ?>

</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
                            <p>
                           <?php if($actionFlag == 1) {echo Html::a('Create Output Table', ['admin/outputtables/create', 'id' =>$intQuestionId ], ['class' => 'btn btn-success']);} ?>
                            <?php echo Html::a('Reset ', ['admin/outputtables/index', 'id' =>$intQuestionId , 'quarter' => $objQuarterDetails['quarter']], ['class' => 'btn btn-default']) ?>
                            <?php if($actionFlag == 1) {echo Html::submitButton('Delete All', ['class' => 'btn btn-danger del-btn', 'style'=>" float:right;", 'disabled'=>true]) ; }?>
                          
                            
                        </div><!-- /.col-md-12 -->
                         <?php echo Html::endForm();?>
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
<?php Url::remember(Url::to(),'outputtable');?>