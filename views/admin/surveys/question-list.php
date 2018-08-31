<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveyTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Questions';

?>


<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">

                    <h2><i class="fa fa-question-circle"></i> <?php echo Html::encode($this->title);?>
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
                                <?php echo Html::a('Dashboard', ['site/index']);?>
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

                    <div class="row">
                        <div class="col-md-12">

                        <div class="panel rounded shadow">
                    <div class="panel-heading">
                          <span><strong><i class="fa fa-edit btn btn-primary btn-xs"></i> : Edit Question </strong></span> &nbsp;
                          <span><strong><i class="fa fa-trash btn btn-danger btn-xs"></i> : Delete Question </strong></span>&nbsp;
                          <span><strong><i class="fa fa-eye btn btn-success btn-xs"></i> : View Question </strong></span>&nbsp;
                          <span><strong><i class="fa fa-history btn btn-warning btn-xs"></i> : Question Audit Trail </strong></span>&nbsp;
                          
                    </div>
                </div>  

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
                           <?php if($objQuarterDetails['distributed'] != 1 && $objQuarterDetails['closed'] != 1 && ($objQuarterDetails['deadline'] == '' || $objQuarterDetails['deadline'] >= date('Y-m-d')))  {?> 
        					           <?php echo Html::a('Create Question', ['admin/surveys/addquestion', 'id' => $survey_template_id , 'qid' =>$objQuarterDetails['quarter'] ], ['class' => 'btn btn-success']) ?>
                            <?php echo Html::a('Node Categories', ['admin/surveys/nodecategories', 'id' => $survey_template_id], ['class' => 'btn btn-primary']) ?>
                          <?php } ?>
                            <?php echo Html::a('Survey Audit Trail', ['admin/surveys/audit_trail', 'id' => $survey_template_id], ['href'=>'javascript:void(0);' ,'class' => 'btn btn-danger']) ?>
                            <?php echo Html::a('Reset', ['questions' , 'id' =>$survey_template_id , 'quarter' => $objQuarterDetails['quarter'] ], ['class' => 'btn btn-default']) ?>
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
			[
				'attribute'=>'question',
				'format' => 'raw',
				'value' => function($data) {
					//return substr(strip_tags($data->question), 0, 100);
					return '<span  data-content="'.strip_tags($data->question).'" data-trigger="hover" data-placement="top" data-toggle="popover" >'.substr(strip_tags($data->question), 0, 100).'</span>';
					//return $data->question;
				},
			],
            'propertyName',
             [
				'header' => 'Action',
				'class' => 'yii\grid\ActionColumn',
				'template' => ($objQuarterDetails['distributed'] != 1 && $objQuarterDetails['closed'] != 1 && ($objQuarterDetails['deadline'] == '' || $objQuarterDetails['deadline'] >= date('Y-m-d'))) ?'{edit} {delete} {view} {audit}' : '{view} {audit}',
				'buttons' => 
					[
						'edit' => function ($url,$model,$key)use($quarter) {
							
							return Html::a('<i class="fa fa-edit"></i>', ['admin/surveys/updatequestion', 'id' => $model->id , 'qid'=>$quarter], ['class' => 'btn btn-primary btn-xs ' ,'data-toggle'=>'tooltip' ,"data-placement"=>"top"]);
						},
						'delete' => function($url, $model, $key) {
							return Html::a('<i class="fa fa-trash"></i>','#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs confirm-delete ' ,'data-toggle'=>'modal' ,"data-placement"=>"top",   "data-id" => $model->id]
							);
						},
						'view' => function($url, $model, $key) use($quarter){
							return Html::a('<i class="fa fa-eye"></i>',['admin/surveys/questiondetails','id' => $model->id,'qid'=>$quarter], ['href'=>'javascript:void(0)' ,'class' => 'btn btn-success btn-xs ' , "data-id" => $model->id]
							);
                        },
             'audit' => function($url, $model, $key) {
                  return Html::a('<i class="fa fa-history"></i>',['admin/question/audit_trail', 'id' => $model->id], ['class' => 'btn btn-warning btn-xs']);
            }
					]
			 ],
        ],
		'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
    ]); ?>
    
</div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
<p>
     <?php if($objQuarterDetails['distributed'] != 1 && $objQuarterDetails['closed'] != 1 && ($objQuarterDetails['deadline'] == '' || $objQuarterDetails['deadline'] >= date('Y-m-d')) )  {
      ?> 
         <?php echo Html::a('Create Question', ['admin/surveys/addquestion', 'id' => $survey_template_id , 'qid' =>$objQuarterDetails['quarter'] ], ['class' => 'btn btn-success']) ?>
         <?php echo Html::a('Node Categories', ['admin/surveys/nodecategories', 'id' => $survey_template_id], ['class' => 'btn btn-primary']) ?>
     <?php } ?>
    <?php echo Html::a('Survey Audit Trail', ['admin/surveys/audit_trail', 'id' => $survey_template_id], ['href'=>'javascript:void(0);' ,'class' => 'btn btn-danger']) ?>
     <?php echo Html::a('Reset', ['questions' , 'id' =>$survey_template_id , 'quarter' => $objQuarterDetails['quarter'] ], ['class' => 'btn btn-default']) ?>
  </p>
                            
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
      <?php Url::remember(Url::to(), 'question');?>