<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\TZTransactRequest;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\widgets\Survey;

/* @var $this yii\web\View */
/* @var $model app\models\Companies */
$this->title 	=	'Question Detail';
$this->registerJsFile('@web/js/pages/jquery.tagsinput.js');
$this->registerCssFile('@web/css/jquery.tagsinput.css');

# Add CSS & JS for this page
$this->registerJsFile('@web/bower_components/smoothness/jquery-ui.js', ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\CoreAsset::className()]]);
// taginputs for adding new column
$this->registerJs("$('#datafieldtemplates-options').tagsInput();");
$actionFlag = 1; // ie action can be performed on survey
?>

<section id="page-content">
<div class="header-content">
        <h2><i class="fa fa-question-circle"></i>  <?php echo $this->title;?>
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
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Surveys',Url::previous('survey'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Questions',Url::previous('question'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                
                 <li class="active"><?php echo $this->title;?></li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
</div>

<div class="body-content animated fadeIn">
<div class="row">

     <div class="col-md-12">

        	 <?php echo $surveyDetails=(Survey::widget(['id' => $model->survey_template_id, 'type' => 'Survey']));?>
           <p>
           <?php if(count($arrColumns)>0) {
    echo Html::a('Output Tables', ['admin/outputtables/index','questid' => $model->id, 'quart' => $objQuarterDetails['quarter']], ['class' => 'btn btn-success']) ;
 }?></p>
            <!-- Start input masks -->
            <div class="panel  rounded shadow">
            <div class="panel-heading">
                 <div class="pull-right">
                            <button class="btn btn-sm" data-container="body" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse">
                            <i class="fa fa-angle-up"></i></button>
                            <button class="btn btn-sm" data-container="body" data-action="remove" data-toggle="tooltip" data-placement="top" data-title="Remove">
                            <i class="fa fa-times"></i></button>
                        </div>
                        <div class="clearfix"></div>
              </div>
                    
                <div class="panel-sub-heading inner-all">
                    <div class="pull-left">
                        <h3 class="lead no-margin">Property Type : <?php if(isset($model->propertyTypes->name)) { echo $model->propertyTypes->name; } ?></h3>
                    </div>
                    
                <div class="clearfix"></div>
                 </div><!-- /.panel-sub-heading -->
                 <div id="accordion">
                <div class="panel-sub-heading inner-all" >
                    <div class="pull-left">
                        <h3 class="lead no-margin">Question :<?php if(isset($model->question) && !empty($model->question)) { echo $model->question; } ?></h3>
                    </div>
                    
                <div class="clearfix"></div>
                 </div><!-- /.panel-sub-heading -->
                 <div class="panel-sub-heading inner-all">
                    <div class="row">
                        <div class="col-md-12 col-sm-8 col-xs-7">
                            <!--<img src="../../../assets/global/img/avatar/35/1.png" alt="..." class="img-circle">-->
                            <h3 class="lead no-margin">Relevant Information :<br/><?php if(isset($model->information) && !empty($model->information)) { echo $model->information; } ?></h3>
                        </div>
                    </div></div>
                </div><!-- /.panel-sub-heading -->
              
            </div><!-- /.panel -->
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
             <!-- Start input fields - horizontal form -->
             <?php if($objQuarterDetails['distributed'] != 1 && $objQuarterDetails['closed'] != 1 &&  ($objQuarterDetails['deadline'] == '' || $objQuarterDetails['deadline'] >= date('Y-m-d')))  {
                $actionFlag = 1;
              ?> 
                    
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
                                <h4>Add New Column</h4>
                         </div>
                        <div class="pull-right">
                            <button class="btn btn-sm" data-container="body" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse">
                            <i class="fa fa-angle-up"></i></button>
                            <button class="btn btn-sm" data-container="body" data-action="remove" data-toggle="tooltip" data-placement="top" data-title="Remove">
                            <i class="fa fa-times"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.panel-heading -->
                    <div class="panel-body no-padding rounded-bottom">
                    <?php $form = ActiveForm::begin([
                      //'method' => 'put',
                        'options' => [
                            'class' => 'form-horizontal',
                            'enableClientValidation' => true
                        ],
                        'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
                            'labelOptions' => ['class' => 'col-sm-2 control-label']
                        ]
                    ]); ?>
                        <div class="form-body">
                       
                                <div class="form-group">
                                    <?php echo $form->field($dataTemplatemodel, 'heading')->textInput([
                                            'class' => 'form-control roundedx',
                                            'maxlength' => true,
                                           'placeholder' => 'heading',
                                    ]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($dataTemplatemodel, 'field_type')->dropDownList(
                                            $arrFieldTypes,[
                                            'class' => 'form-control rounded ajax',
                                            'maxlength' => true,
                                            //'id' => 'field_type'
                                    ]);?>
                                </div>
                                <div class="form-group option-class" style="display:none;">
                                    <?php echo $form->field($dataTemplatemodel, 'options', ['enableAjaxValidation' => 'true'])->textInput([
                                            'placeholder' => 'Options',
                                            'class' => 'form-control rounded ajax',
                                            'maxlength' => true,
                                            //'id' => 'options'
                                    ]);?>
                                </div>
                         </div><!-- /.form-body -->
                        
                        <div class="form-footer">
                                <div class="pull-right">
                                    <?php //echo Html::a('Cancel', ['index'], ['class' => 'btn btn-danger mr-5']);?>
                                    <?php echo Html::submitButton('Create' , ['class' => 'btn btn-success']);?>
                                </div>
                                <div class="clearfix"></div>
                         </div><!-- /.form-footer -->
                       <?php ActiveForm::end();?>
                    </div><!-- /.panel-body -->

                </div><!-- /.panel -->
              <?php } ?>


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
            <div style="text-align:center;" class="hideOption" id="showLoader"><?php echo Html::img('@web/images/loader.gif');?></div>
              <div class="panel rounded shadow">
                    <div class="panel-heading">
                          <span><strong><i class="fa fa-arrow-right"></i> : Captured </strong></span> &nbsp;
                          <span><strong><i class="fa fa-random"></i> : Released </strong></span>&nbsp;
                          <span><strong><i class="fa fa-check-circle"></i> : Enabled </strong></span>&nbsp;
                          <span><strong><i class="fa fa-times-circle"></i> : Disabled </strong></span>&nbsp;
                          <span><strong><i class="fa fa-plus-square"></i> : Included </strong></span> &nbsp;
                          <span><strong><i class="fa fa-minus-square"></i> : Excluded </strong></span>&nbsp;
                          <span><strong><i class="fa fa-arrow-right reorder"></i><i class="fa fa-arrow-left"></i> : Change Order</strong></span> 
                    </div>
                </div>              
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
                                <h4>Table</h4>
                         </div>
                        <div class="pull-right">
                            <button class="btn btn-sm" data-container="body" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse">
                            <i class="fa fa-angle-up"></i></button>
                            <button class="btn btn-sm" data-container="body" data-action="remove" data-toggle="tooltip" data-placement="top" data-title="Remove">
                            <i class="fa fa-times"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body no-padding rounded-bottom">
                    <?php Pjax::begin(['id' => 'output-table']);
                    $this->registerJs(" $('tr#dataColumns').sortable({
        items: 'td',
        cursor: 'move',
        opacity: 0.6,
        update: function() {
            sendOrderToServer();
        }
    });"); ?>
                     <div class="table-responsive ">
                        <table class="table table-success table-condensed table-bordered">
                            <thead>
                                <th>Capture Level</th>
                                <th>Nodes</th>
                                <?php foreach($arrColumns as $col) {?>
                                    <th><?php echo Yii::$app->formatter->asRaw($col['heading']).
                                    '<br/><strong><span style="color:black;">'.$col['field_type'].'</span></strong>';?></th>
                                <?php }?>
                               <!-- <th><?php echo Html::a('Add new Column', ['#det']);?></th>-->
                            </thead>
                            <tbody >
                              <?php  if($actionFlag == 1) {?>
                                    <tr>
                                     <td></td>
                                     <td></td>
                                     <?php foreach($arrColumns as $col) {?>
                                            <td>
                                            <?php echo Html::button('<i class="fa fa-edit"></i>',['value' => Url::to(['updatecolumn', 'id'=>$col['id']]), 'class' => 'showModalButton btn btn-primary btn-xs', 'title' => 'Update Data Column']).
                                            Html::a('<i class="fa fa-trash"></i>','#', ['href'=>'javascript:void(0)' ,'data-id'=>$col['id'] ,'class' => 'btn btn-danger btn-xs confirm-delete', 'data-url' => Url::to(['deletecolumn']), 'style' => 'margin :5px;']);?></td>
                                    <?php }?>
                                    </tr>
                                    <?php }?>
                                 <?php foreach($arrNodes as $node) { $i=1;?>
                                   <tr>
                                     <td>
                                     <?php 
                                     if($node['capture_level'] == '1') { 
                                      if($actionFlag == 1) {
                                          echo Html::a('<i class="fa fa-arrow-right optA"></i>', '#', 
                                            ['href' => 'javascript:void(0);', 
                                            'onClick' => 'javascript:change_capture('.$node['capture_level'].', '.$node['id'].');']);
                                      } else {
                                        echo Html::a('<i class="fa fa-arrow-right fa-disabled optA1"></i>', '#', 
                                            ['href' => 'javascript:void(0);' ,'data-toggle'=>'modal']);
                                      }
                                     } else {
                                          if($actionFlag == 1) {
                                          echo Html::a('<i class="fa fa-random optB"></i>', '#', 
                                            ['href' => 'javascript:void(0);', 
                                            'onClick' => 'javascript:change_capture('.$node['capture_level'].', '.$node['id'].');']);
                                      } else {
                                        echo Html::a('<i class="fa fa-random fa-disabled optB1"></i>', '#', 
                                            ['href' => 'javascript:void(0);' ,'data-toggle'=>'modal']);
                                      }
                                      }?>
                                     </td>
                                     <td class="<?php echo ($node['survey_template_question_node_id'] == NULL || $node['survey_template_question_node_id'] == '') ? 'classDark' : ''?>">
                                     <?php echo Html::a(($node['included'] == 1) ? '<i class="fa fa-plus-square"></i>' : '<i class="fa fa-minus-square"></i>', '#', 
                                            ['href' => 'javascript:void(0);', 
                                            'onClick' => ($actionFlag == 1) ? 'javascript:change_include_exclude('.$node['included'].', '.$node['id'].');' : '',
                                            ]);
                                      ?>
                                     <?php echo $node['name']; ?>
                                     </td>
                                     <?php foreach($arrColumns as $col) {$boolFlag = 0; ?>
                                        <td>
                                            <?php echo 'Column '.$i;?>
                                            <?php if(!empty($col['exclusions']) ) {
                                                $arrTempId = $arrNodeId = [];
                                                foreach($col['exclusions'] as $exc) {
                                                        $arrTempId[] = $exc['data_field_template_id'];
                                                        $arrNodeId[] = $exc['survey_template_question_node_id'];
                                                }
                                                if(in_array($col['id'], $arrTempId) && in_array($node['id'], $arrNodeId)) {
                                                    $boolFlag =1;
                                                }

                                            } 
                                            echo Html::a(($boolFlag == 1) ? '<i class="fa fa-times-circle"></i>':'<i class="fa fa-check-circle"></i>','#', [
                                                        'href'=>'javascript:void(0)' ,
                                                        'data-column'=>$col['id'] ,
                                                        'data-node'=>$node['id'] ,
                                                        'class' =>  ($actionFlag == 1) ? 'enabledisable' : '', 
                                                        'data-excluded' => $boolFlag]);
                                            ?>
                                            
                                        </td>
                                     <?php  $i++;}?>
                                   </tr>

                                 <?php }?>
                                 <?php  if($actionFlag == 1) {?>  
                                 <tr id="dataColumns">
                                     <td ></td>
                                     <td ></td>
                                     <?php foreach($arrColumns as $col) {?>
                                            <td class="sectionsid" id="sectionsid_<?php echo $col['id'];?>">
                                            <?php //if( $col['order'] < count($arrColumns)) {
                                            echo Html::a('<i class="fa fa-arrow-right reorder"></i>','#', ['href'=>'javascript:void(0)' ,'data-order'=>$col['order'] ,'class' => 'moveuplink', 'data-total' => count($arrColumns)]).'<br/>';
                                            //}?>
                                            <?php //if($col['order'] != '1') {
                                                echo Html::a('<i class="fa fa-arrow-left"></i>','#', ['href'=>'javascript:void(0)' ,'data-order'=>$col['order'] ,'class' => 'movedownlink', 'data-url' => 'deletecolumn']);
                                                // }?></td>
                                    <?php }?>
                                    </tr>  
                                    <?php }?>
                            </tbody>
                            
                        </table>
                    </div> 
                    <?php Pjax::end(); ?>
                    </div>
                </div>
            <!-- End input masks -->
             
      </div> 
    </div><!-- /.row -->

     
                  
        </div> 

<script type="text/javascript">
    //changing column position
   
</script>
<style type="text/css">
  .MsoNormal {
    margin: 10px !important;
    //width: auto !important;
}

</style>

