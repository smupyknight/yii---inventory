<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Surveys;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\widgets\Survey;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveyTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of Contributors';

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
                            <?php echo Html::a('Manage Surveys', Url::previous('survey'));?>
                             <i class="fa fa-angle-right"></i>
                            </li>
                             <li class="active"><?php echo Html::encode($this->title);?></li>
                        </ol>
                    </div><!-- /.breadcrumb-wrapper -->
                </div><!-- /.header-content -->
                <!--/ End page header -->

                <!-- Start body content -->
                <div class="body-content animated fadeIn">
            <?php echo Html::beginForm(Url::to(['admin/surveys/notifycontributor']), 'post',['id'=>'header-search-form']) ?>
                    <div class="row">
                        <div class="col-md-12">

                         <div class="panel rounded shadow">
                    <div class="panel-heading">
                          <span><strong><i class="fa fa-check btn btn-success btn-xs"></i> : Survey Distributed / Completed </strong></span> &nbsp;
                          <span><strong><i class="fa fa-times btn btn-danger btn-xs"></i> : Not yet distributed / Incomplete </strong></span>&nbsp;
                          <span><strong><i class="fa fa-edit btn btn-primary btn-xs"></i> : Edit Contributor </strong></span>&nbsp;
                          <span><strong><i class="fa fa-envelope-o btn btn-warning btn-xs"></i> : Resend Notification Email </strong></span>&nbsp;

                    </div>
                </div>
							             <!-- Start danger color table -->
                           <div class="panel panel-default panel-blog rounded shadow">
    <div class="panel-body">
      <div class="row">
           <div class="col-md-6">
                <label>Survey Name :</label>
                <?php echo $surveyDetail['name']; ?>
            </div>
            <div class="col-md-6">
                <label>Publication :</label>
                <?php if(isset($surveyDetail['publication'])) {echo $surveyDetail['publication'];} ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
                <label>Who can contribute :</label>
                <?php if(isset($surveyDetail['contributor_type'])) {echo $surveyDetail['contributor_type'];} ?>
            </div>
            <div class="col-md-6">
                <label>Deadline :</label>
                <?php if($surveyDetail['deadline']!= "") {echo date('d M,Y',strtotime($surveyDetail['deadline']));} else {echo 'Not Set';} ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Completed :</label>
                <?php if($surveyDetail['closed'] == 1) { echo 'Closed';} ?>
            </div>
        </div>
    </div>
</div>
                           <!-- Start danger color table -->
          <p>
        			<?php if($surveyDetail['deadline']== "" && $surveyDetail['closed'] == 0) {echo Html::a('Set Deadline', '#', ['href'=>'javascript:void(0);','class' => 'btn btn-success set-dealine', 'data-toggle'=>'modal']); }?>
              <?php echo Html::a('Reset', ['contributors', 'id' => $surveyDetail['id'], 'quarter_id' =>$intQuarterId], ['class' => 'btn btn-default']); ?>
              <?php if($surveyDetail['closed'] == 0) {echo Html::a('Close Survey','#', ['href'=>'javascript:void(0)','class' => 'btn btn-default close-survey', 'data-toggle'=>'modal']); } ?>
              <?php echo Html::submitButton('Resend Email Notification', ['class' => 'btn btn-danger email-notify' ,'style'=>"float:right;", 'disabled'=>true]) ?>
         </p>
                          <div class="clearfix"></div>
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
            				<div class="alert alert-success">
               				 <span><i class="icon fa fa-check"></i> </span>
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <span> <?php echo Yii::$app->session->getFlash('success'); ?></span>
                            </div>
                    	<?php endif; ?>
                         <div style="text-align:center;" class="hideOption" id="showLoader"><?php echo Html::img('@web/images/loader.gif');?></div>
                            <div class="table-responsive mb-20">
                            <?php Pjax::begin(['id'=>'contributor-grid']);?>
                            <?php echo Html::input('hidden', 'survey_id',$surveyDetail['id'],['id' => 'survey_id']) ?>
                            <?php echo Html::input('hidden', 'quarter_id',$surveyDetail['quarter_id'],['id' => 'quarter_id']) ?>
							<?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			['class' => 'yii\grid\CheckboxColumn','checkboxOptions' => function($model, $key, $index, $column) {
        //print_r($model);exit;
                  return [ 'value' => $model['id'], 'class' => 'chkcontributor'];
            }],
            ['class' => 'yii\grid\SerialColumn'],
			'contributor_name',
             /*[
				'attribute' => 'category_name',
				'value' => 'category.name'
			],*/
			'distribution_method',
			[
				'attribute' => 'distributed',
				'format' => 'raw',
				'value' => function($data) {
					if($data['distributed'] == '1') {
						return '<i class="fa fa-check btn-success"></i>';
					} else if($data['distributed'] == '0') {
            return '<i class="fa fa-times btn-danger"></i>';
          }else {
						return 'Survey not yet distributed';
					}
				}
			],
		[
				'attribute' => 'completed',
				'format' => 'raw',
				'filter' => ['0'=>'No', '1' => 'Yes'],
				//'visible' => isset($dataProvider->getModels()[0]) ? Surveys::checkIsDistributed($dataProvider->getModels()[0]) : true,
				'value' => function($data) {
					if($data['completed'] == '1') {
						return '<i class="fa fa-check btn-success"></i>';
					} else {
						return '<i class="fa fa-times btn-danger"></i>';
					}
				}
			],
			 [
				'header' => 'Action',
				'class' => 'yii\grid\ActionColumn',
				'template' =>'{edit} {resend}',
				'buttons' =>
					[
						'edit' => function ($url,$model,$key) {
							return Html::a('<i class="fa fa-edit"></i>', ['admin/contributors/update', 'id' => $model['user_id']], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
						},
						'resend' =>function ($url,$model,$key) use($surveyDetail) {
							if($surveyDetail['closed'] == 0) {
								return Html::a('<i class="fa fa-envelope-o"></i>','#', ['href' => 'javascript:void(0);','class' => 'btn btn-warning btn-xs send-mail' ,'data-toggle'=>'tooltip' ,"data-contributor"=>$model['id']]);
							}
						},

					]
			 ],
        ],
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success', 'id' => 'contributor-tablegrid'],
    ]); ?>
    <?php Pjax::end();?>
</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->

    <p>
        <?php if($surveyDetail['deadline']== "" && $surveyDetail['closed'] == 0) {echo Html::a('Set Deadline', '#', ['href'=>'javascript:void(0);','class' => 'btn btn-success set-dealine', 'data-toggle'=>'modal']); }?>
        <?php echo Html::a('Reset', ['contributors', 'id' => $surveyDetail['id'], 'quarter_id' => $intQuarterId], ['class' => 'btn btn-default']); ?>
        <?php if($surveyDetail['closed'] == 0) {echo Html::a('Close Survey','#', ['href'=>'javascript:void(0)','class' => 'btn btn-default close-survey', 'data-toggle'=>'modal']); } ?>
        <?php echo Html::submitButton('Resend Email Notification', ['class' => 'btn btn-danger email-notify' ,'style'=>"float:right;", 'disabled'=>true]) ?>
     </p>
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <?php echo Html::endForm();?>
                <!--/ End body content -->
