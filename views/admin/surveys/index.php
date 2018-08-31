<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveyTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Surveys';
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
                             <li class="active"><?php echo Html::encode($this->title);?></li>
                        </ol>
                    </div><!-- /.breadcrumb-wrapper -->
                </div><!-- /.header-content -->
                <!--/ End page header -->

                <!-- Start body content -->
                <div class="body-content animated fadeIn">

                    <div class="row">
                        <div class="col-md-12">

                        <div class="panel rounded shadow">
                    <div class="panel-heading">
                          <span><strong><i class="fa fa-question btn btn-primary btn-xs"></i> : survey Questions </strong></span> &nbsp;
                          <span><strong><i class="fa fa-users btn btn-danger btn-xs"></i> : Contributors </strong></span>&nbsp;
                          <span><strong><i class="fa fa-edit btn btn-success btn-xs"></i> : Edit Survey </strong></span>&nbsp;
                          <span><strong><i class="fa fa-history btn btn-warning btn-xs"></i> : Survey Audit Trail </strong></span>&nbsp;
                          <span><strong><i class="fa fa-table btn btn-info btn-xs"></i> : Results </strong></span>&nbsp;
                          <span><strong><i class="fa fa-trash btn btn-danger btn-xs"></i> : Delete Survey </strong></span>
                    </div>
                </div>
							<?php echo $this->render('_search', [
								'model' => $searchModel,
								'quarters' => $quarters,
								'publications' => $publications
								]);?>

                 <?php echo Html::beginForm(Url::to(['admin/surveys/distributesurvey']), 'post',['id'=>'header-search-form']) ?>
                           <!-- Start danger color table -->
                  <?php echo Html::hiddenInput('curr_quarter',isset($_GET['SurveyTemplatesSearch']['quarter']) ? $_GET['SurveyTemplatesSearch']['quarter'] : array_values($quarters)[0]);?>
                <p>
        					<?php echo Html::a('Create Survey', ['create'], ['class' => 'btn btn-success']) ?>
                  <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                  <?php echo Html::submitButton('Distribute Survey',['class' => 'btn btn-danger distribute-btn right', 'disabled' => true]) ?>
                  <?php echo Html::submitButton('Set Deadline',['class' => 'btn btn-primary distribute-btn right', 'disabled' => true, 'onclick' => 'return set_deadline(); ']) ?>
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
                      <input id="deadline_form" type="hidden" name="deadline" value="<?=date('Y-m-d');?>">

                            <div class="table-responsive mb-20">
                            <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [

            'attribute' => 'distributable',
            'header' => Html::checkBox('selection_all', false, [
            'class' => 'select-on-check-all-box',
           ]),
            'format' => 'raw',
            'visible' => true,
            'value' => function($data) {
                if($data->distributed != '1') {
                  return Html::checkbox('selection[]',false , ['value' => $data->id, 'class' => 'chkdistribute']);
                } else {
                  return '';
                }
              }],
       'name',
			'publication',
             /*[
				'attribute' => 'category_name',
				'value' => 'category.name'
			],*/
			'category',
			'quarter_name',
      [
        'attribute' => 'distributed',
        'format' => 'raw',
        'value' => function($data) {
          if($data['distributed'] == '1') {
            return '<i class="fa fa-check btn-success"></i>';
          } else {
            return '<i class="fa fa-times btn-danger"></i>';
          }
        }
      ],
			[
				'attribute' => 'deadline',
				//'format' => ['date', 'php:d M,Y'],

				'value' => function($data) {
					if($data ->deadline != '') {
					return date('d M,Y', strtotime($data ->deadline));
					} else {
						return 'Not Set';
					}
				}
			],

            [
				'header' => 'Action',
				'class' => 'yii\grid\ActionColumn',
				'template' => '{question} {contributor} {edit} {audit} {output} {deletesv}',
				'buttons' =>
					[
						'question' => function ($url,$model,$key) {
							return Html::a('<i class="fa fa-question"></i>', ['questions', 'id' => $model->id, 'quarter' => $model->quarter_name], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
						},
						'contributor' => function($url, $model, $key) {
							return Html::a('<i class="fa fa-users"></i>',['contributors', 'id' => $model->id, 'quarter_id' => $model->quarter_id], ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs ' , "data-id" => $model->quarter_id]
							);
						},
						'edit' => function($url, $model, $key) {
							if($model->distributed == 0) {
							return Html::a('<i class="fa fa-edit"></i>',['update', 'id' => $model->id], ['class' => 'btn btn-success btn-xs']
							);
						  }
                        },
              'audit' => function($url, $model, $key) {
                            return Html::a('<i class="fa fa-history"></i>',['audit_trail', 'id' => $model->id], ['class' => 'btn btn-warning btn-xs']);
              },
              'output' => function($url, $model, $key) {
              return Html::a('<i class="fa fa-table"></i>',['outputtables', 'id' => $model->id, 'qid' => $model->quarter_name], ['href'=>'javascript:void(0)' ,'class' => 'btn btn-info btn-xs ' ]
              );
              },
              'deletesv' => function($url, $model, $key) {
                            return Html::a('<i class="fa fa-trash"></i>',['deletesv', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs', 'onclick' => 'return confirm(\'Are you sure you want to delete this survey?\');']);
              },
					]
			 ],
        ],
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
    ]); ?>

</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
                          <p>
                            <?php echo Html::a('Create Survey', ['create'], ['class' => 'btn btn-success']) ?>
                            <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                          </p>
                       <?php echo Html::endForm();?>
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
      <?php Url::remember(Url::to(), 'survey');?>

<script type="text/javascript">
  function set_deadline(){
    var newFormURL = '<?=Url::to(['admin/surveys/setdeadline']);?>';
    $('#header-search-form').attr('action', newFormURL);
    $('#set_deadline_modal').modal('show');
    return false;
  }
</script>


<div class="modal fade" id="set_deadline_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
          <h4 class="modal-title">Survey Deadline</h4>
      </div>
      <div class="modal-body">
            <div class="row">
              <div class="col-md-2">Deadline <span class="deleted">*</span></div>
              <div class="col-md-6">
                <div class="input-group date">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                  </span>
                  <input type="text" id="deadline" class="form-control" name="deadline" value="<?=date('Y-m-d');?>" onchange="$('#deadline_form').val($(this).val())">
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
           <a class="btn btn-default" href="#" data-dismiss="modal">Cancel</a>
           <button type="button" id="save-deadline" class="btn btn-primary" onclick="$('#header-search-form').submit()">Set deadline</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
