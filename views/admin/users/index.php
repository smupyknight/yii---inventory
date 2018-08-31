<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Rodeuserssearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contributors';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($dataProvider->getModels());die;
?>

<section id="page-content">
                <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-users"></i> Contributors</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li class="active">Manage Contributors</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
	<!--/ End page header -->

    <!-- Start body content -->
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

            <div class="panel rounded shadow">
                    <div class="panel-heading">
                          <span><strong><i class="fa fa-eye btn btn-success btn-xs"></i> : Contributor Information </strong></span> &nbsp;
                          <span><strong><i class="fa fa-pencil btn btn-primary btn-xs"></i> : Update Email/Password </strong></span>&nbsp;
                          <span><strong><i class="fa fa-pencil-square-o btn btn-primary btn-xs"></i> : Update Profile </strong></span>&nbsp;
                          <span><strong><i class="fa fa-trash btn btn-danger btn-xs"></i> :Delete User </strong></span>&nbsp;
                          <span><strong><i class="fa fa-key btn btn-warning btn-xs"></i> : Assign Nodes </strong></span>&nbsp;
                          <span><strong><i class="fa fa-street-view btn btn-warning btn-xs"></i> : View Nodes </strong></span>&nbsp;

                    </div>
                </div>
            <p>
        		<?php echo Html::a('Create Users', ['create'], ['class' => 'btn btn-success']) ?>
                <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
    		</p>
             <div class="table-responsive mb-20">
			<?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' =>['class' => 'table table-striped table-bordered table-success'],
        				'rowOptions'=>function ($model, $key, $index, $grid){
          				  if($model->disabled=='2'){
          					return ['class'=>'deleted'];
        				 }},
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'fullName',
                        'value'=>'contributors.fullName',
			                  'options' => ['width' => '100'],
                    ],
                    'login',
                    'email:email',
		                 [
                        'attribute' => 'distribution_method',
                        'value'=>'contributors.distribution_method',
                        'filter'=>array("online"=>"online","regular mail"=>"regular mail"),
			                  'options' => ['width' => '100'],
                    ],
                    [
                      'attribute' => 'contributors.company_id',
                      'value'=>'contributors.companies.name',
                      'filter' => Html::dropDownList('company_id', $company, $companies, ['name'=>'company_id', 'class' => 'form-control']),
                      'options' => ['width' => '100'],
                    ],
                    [
                        'attribute' => 'contributor_type',
                        'value'=>'contributors.contributor_type',
            						'filter'=>array(""=>"All","Both"=>"Both","Broker"=>"Broker","Owner"=>"Owner"),
            						'options' => ['width' => '100'],
                    ],
	                  [
          						'attribute'=>'publication',
          						'value'=>'contributors.publication',
          					],
	                  [
          						'attribute'=>'disabled',
                      'filter' => array(""=>"All",'0'=> 'Inactive', '1' => 'Active', '2' => 'Deleted'),
          						'value'=>'contributorstatus',
          					],
                    [
          						'class' => 'yii\grid\ActionColumn',
          						'template' => '{view} {update} {update_profile} {delete} {link} {nodes} {viewnode}',
                			'buttons' =>
          						[
          							'view' => function ($url,$searchModel,$key) {
          							        return Html::a('<i class="fa fa-eye"></i>', ['admin/users/view', 'id'=>$key], ['title'=>'View','class'=>'btn btn-success btn-xs']);
               					 },
          							'update' => function ($url,$searchModel,$key) {
          	             				return Html::a('<i class="fa fa-pencil"></i>', ['admin/users/update', 'id'=>$key], ['data-toggle' => 'modal', 'title'=>'Update','class'=>'btn btn-primary btn-xs']);
                        },
          							'update_profile' => function ($url,$searchModel,$key) {
          											return Html::a('<i class="fa fa-pencil-square-o"></i>', ['admin/contributors/update', 'id'=>$key], ['data-toggle' => 'modal', 'title'=>'Update profile','class'=>'btn btn-primary btn-xs']);
          						  },
          							'delete' => function ($url,$searchModel,$key) {
          								if($searchModel->disabled!='2')
          								{
          											return Html::a('<i class="fa fa-trash"></i>', '#', ['href'=>'javascript:void(0)' ,'class' => 'btn btn-danger btn-xs confirm-delete ','data-toggle'=>'modal' ,"data-placement"=>"top",   "data-id" => $key]);
          								}
          							},
          							'nodes' => function ($url,$searchModel,$key) {
            								if($searchModel->disabled!='2')
            								{
            									return Html::a('<i class="fa fa-key"></i>', ['admin/users/assignnode', 'id'=>isset($searchModel->contributors->id)?$searchModel->contributors->id:null], ['title'=>'Assign Nodes','class'=>'btn btn-warning btn-xs']);
            								}
                 					 },
                           'viewnode' => function ($url,$searchModel,$key) {
                              return Html::a('<i class="fa fa-street-view"></i>', ['admin/users/viewnodes', 'id'=>isset($searchModel->contributors->id)?$searchModel->contributors->id:null], ['title'=>'View Nodes','class'=>'btn btn-warning btn-xs']);

                           },
          							'options' => ['width' => '500']
                			],
        						'header'=>'Action',
        					],
                ],
            ]); ?>

    </div><!-- /.table-responsive -->
                            <!--/ End danger color table -->
<p>
     <?php echo Html::a('Create Users', ['create'], ['class' => 'btn btn-success']) ?>
     <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
 </p>

                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->

<!--</div>-->
