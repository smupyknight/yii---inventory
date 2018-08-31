<?php
use app\models\PropertyTypes;
use app\models\Contributors;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Assign node to contributor ';

$this->registerJsFile('@web/bower_components/smoothness/jquery-ui.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\CoreAsset::className()]]);
$this->registerJsFile('@web/js/jquery.tree.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile('@web/css/jquery.tree.css');
$this->registerCssFile('@web/css/tree.css');
//$this->registerCssFile('https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css');
$userId	=	Yii::$app->getRequest()->getQueryParam('id');


?>

<div class="header-content">
        <h2><i class="fa fa-users"></i> Contributor</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li><?php echo Html::a('Manage Contributors',['admin/users/index']);?>
                 <i class="fa fa-angle-right"></i></li>
                 <li class="active">Assign Nodes</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

<div class="body-content animated fadeIn user-edit">
    <div class="row">
        <div class="col-md-12">
        	<div class="left_panel">
            <!-- Start input masks -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Assign nodes to contributor</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        <button class="btn btn-sm" data-action="remove" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                 <?php /*$form = ActiveForm::begin([
                            "class"=>"form-horizontal editUser",
                            ]); */?>
                <?php
                    $session = Yii::$app->session;
                    $success	= $session->hasFlash('success');
                    if(!empty($success)) {
                   ?>
                    <div class="alert alert-success">
                        <span><i class="icon fa fa-check"></i> </span>
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <span>
                        <?php echo $session->getFlash('success'); ?> </span>
                    </div>
                    <?php } ?>
                        <div class="row">
                                    <div class="col-sm-6">
                                    <?php
									$strContributorName=Contributors::find()->where(['id'=>$userId])->one();
                                    $contributorId = $strContributorName->id;
									if(!empty($strContributorName))
									{
									?>
                                      <label><strong>Contributor : </strong><?php echo $strContributorName->fullname; ?> </label>
                                      <?php
									}
									  ?>
                                      </div>
                        </div>
                         <?php $form = ActiveForm::begin([
                                "class"=>"form-horizontal proprty",
                                "action"=>Yii::$app->request->baseUrl.'/admin/users/assignnode/'.$contributorId,
                                ]); ?>
                             <input type="hidden" name="contributor_id" id="contributor_id" value="<?php echo $strContributorName->id; ?>">
                              <input type="hidden" name="cont_id" id="cont_id" value="<?php echo $strContributorName->id; ?>">
                        <div class="row">
                            <div class="col-sm-5">
                                          <label><strong>Property type :</strong></label>
                                          <?php
                                            $proprtyType=PropertyTypes::find()->where(['status'=>'Active'])->orderBy('name')->all();
                                            $listData=ArrayHelper::map($proprtyType,'id','name');
                                            echo $form->field($model, 'id')->dropDownList($listData ,['options'=>[$intPropertyId => ['Selected' =>'selected' ]]])->label(false); //['prompt'=>'Select...']
                                          ?>

                            </div>
                             <?= Html::submitButton('Filter', ['class' => 'btn btn-success','name'=>'filter','value'=>'filter_property','style'=>'margin-top:25px;']) ?>

                        </div>

                        <?php // ActiveForm::end(); ?>
                        <?php //if(!empty($intPropertyId)) { ?>
		                    <div id="property">
                        	<?php //$strNameProprtyType=PropertyTypes::find()->where(['id'=>$intPropertyId])->one(); ?>
                        </div>
                        <?php //} ?>
                        <button type="button" class="btn btn-success" name="button" onclick="toggleBoxes()">Check All/Uncheck All</button>
                        <br>
                        <div id="example-2">
                            <div>
                            </div>
                        </div>
                        <div class="pull-right">
                                    <?= Html::submitButton('Assign node', ['class' => 'btn btn-success','name'=>'assign','value'=>'assign_nodes' ]) ?>
                                    <?= Html::a('Cancel', Yii::$app->request->referrer, ['class'=>'btn btn-danger mr-5']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div><!-- /.panel-body  -->
            </div><!-- /.panel -->
            <!-- End input masks -->
            </div><!-- left panel-->
      	</div>
    </div><!-- /.row -->
</div>

<script type="application/javascript">
function toggleBoxes(){
  $('#example-2 input[type=checkbox]').each(function(i, elem){
    if(elem.checked){
      elem.checked = false
    }else{
      elem.checked = true
    }
  });
}
	$( document ).ready(function() {


		//$( "#rodeusers-id" ).change(function() {
			var val = $('#rodeusers-id').val();
			var contributorId	=	$('#contributor_id').val();
			$('#example-2 div').tree({
				lazyLoading: true,
                //dataType: 'json',
				nodesLazyUrl: '/admin/users/childnode?id='+val+'&contributor_id='+contributorId,
				nodesInitUrl: '/admin/users/initnode?id='+val+'&contributor_id='+contributorId,
				onCheck: {
							 //ancestors: 'checkIfFull',
							 ancestors: 'check',
							 descendants: 'check',
							  //others: 'uncheck'
						 },
				onUncheck: {
							//ancestors: 'uncheck'
						}
			});

		//});
	});
</script>
