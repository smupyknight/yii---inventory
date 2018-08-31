<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Contributors;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Companiessearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="page-content">
    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-globe"></i> <?php echo Html::encode($this->title);?></h2>
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
                <p>
                    <?= Html::a('Add Company', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                </p>
                <?php	
					$session = Yii::$app->session;
					if(Yii::$app->session->hasFlash('success')){ ?>
                        <div class="alert alert-success alert-dismissable">
                         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <span>
                                <i class="icon fa fa-check"></i> 
                                <?php echo Yii::$app->session->getFlash('success'); ?>
                            </span>
                        </div>
				<?php } ?>
                <div class="table-responsive mb-20">
                    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
					'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
                    'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    'name',
                    //'physical_address',
                    //'postal_address',
                    //'postal_code',
                     'phone_number',
                     'fax_number',
                     'email:email',
                     'contributor_code',
                    // 'display_code',
						[
							'class' => 'yii\grid\ActionColumn',
							'header'=> 'Action',
							'template' => '{view} {update} {delete} {contributors}',
							'buttons'=>[
							'view' => function ($url,$searchModel,$key) {
	               				return Html::a('<i class="fa fa-eye"></i>', ['admin/companies/view', 'id'=>$key], ['data-toggle' => 'modal', 'title'=>'View','class'=>'btn btn-success btn-xs']);
				},
									'update' => function ($url,$searchModel,$key) {
	               				return Html::a('<i class="fa fa-pencil"></i>', ['admin/companies/update', 'id'=>$key], ['data-toggle' => 'modal', 'title'=>'Update','class'=>'btn btn-primary btn-xs']);
				},
									'delete' => function ($url,$searchModel,$key) {
										$intContributorCount = Contributors::find()->where(['company_id' => $key])->orderBy('id')->count();	
										if($intContributorCount==0)
										{
											$target	=	".model-delete";
											return Html::a('<i class="fa fa-trash"></i>', '#', ['href'=>'javascript:void(0)' ,'title'=>'Delete','class' => 'btn btn-danger btn-xs confirm-delete','data-toggle'=>'modal' ,"data-placement"=>"top",   "data-id" => $key]);
										}
										else
										{
											$target	=	".modal-cant-del";
											return Html::a('<i class="fa fa-trash"></i>', '#', ['href'=>'javascript:void(0)' ,'title'=>'Delete','class' => 'btn btn-danger btn-xs ','data-toggle'=>'modal' ,"data-placement"=>"top", "data-target"=>$target,  "data-id" => $key]);
										}
											
												},
												
								'contributors' => function ($url,$searchModel,$key) {
	               				return Html::a('<i class="fa fa-user"></i>', ['admin/users/index', 'company_id'=>$key], ['data-toggle' => 'modal', 'title'=>'Get contributors','class'=>'btn btn-warning btn-xs']);
				},
							
							],
							'options' => ['width' => '110']
						],
                    ],
                    ]); ?>
                </div>
                <p>
                    <?= Html::a('Add Company', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                </p>
            </div><!-- /.table-responsive -->
                <!--/ End danger color table --> 
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
