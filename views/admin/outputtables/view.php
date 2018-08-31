<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\widgets\Survey;
use yii\helpers\Url;
$this->title = 'View Output Table';
?>
<section id="page-content">
<div class="header-content">
        <h2><i class="fa fa-table"></i> Output Table</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
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
                <li>
                    <?php echo Html::a('Manage Output Tables', Url::previous('outputtable'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">View </li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
</div>

 

<div class="body-content animated fadeIn">
    <div class="row">
        <div class="col-md-12">

        <?php echo $surveyDetails=(Survey::widget(['id' => $model->survey_template_question_id, 'type' => 'Question']));?>
         <!-- Start input masks -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title"> <strong> Output Table Detail<?php //if(isset($model->contributors->fullName)) { echo $model->contributors->fullName; } else { echo '-'; } ?></strong></h3>
                    </div>
                    <div class="clearfix"></div>
                </div>

            <div class="panel-body">
                <div class="form-group">                          
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>Heading :</strong></label><br/>
                                 <?php if(isset($model->heading)) { echo $model->heading; } else { echo '_'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Heading :</strong></label><br/>
                                 <?php if(isset($model->sub_heading)) { echo $model->sub_heading; } else { echo '_'; } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>Parent Node Visibility :</strong></label><br/>
                                 <?php if(isset($model->parent_node_visibility)) { echo $model->parent_node_visibility; } else { echo '_'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Output Column :</strong></label><br/>
                                 <?php if(isset($model->output_column)) { echo $model->output_column; } else { echo '_'; }
                                 if($model->output_column == 'Difference') { echo '<strong>('.$model->difference_presentation.')</strong>';} ?>
                            </div>
                        </div>
                        <?php if($model->output_column == 'Difference') { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>First Field :</strong></label><br/>
                                 <?php echo $model->getFieldId($model->id , $model->first_field_id);  ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Last Field :</strong></label><br/>
                                 <?php echo $model->getFieldId($model->id , $model->last_field_id);  ?>
                            </div>
                        </div>
                        <?php }?>
                         <div class="row">
                            <div class="col-md-6">
                                <label><strong>Standard Deviation Limit :</strong></label><br/>
                                 <?php if(isset($model->sd)) { echo $model->sd; } else { echo '_'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Show Contributor Code? :</strong></label><br/>
                                 <?php if($model->contributor_code == '1') { echo 'Yes'; } else { echo 'No'; } ?>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <label><strong>Show a , b , r Values? :</strong></label><br/>
                                 <?php if($model->a_b_r == '1') { echo 'Yes'; } else { echo 'No'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Show Parent Average? :</strong></label><br/>
                                 <?php if($model->show_parent_average == '1') { echo 'Yes'; } else { echo 'No'; } ?>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-md-6">
                                <label><strong>Created At :</strong></label><br/>
                                 <?php echo $model->created_at; ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Updated At :</strong></label><br/>
                                 <?php echo $model->updated_at; ?>
                            </div>
                         </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="table-responsive ">
            <table class="table table-success table-condensed table-bordered">
            <thead>
                <th><?php echo $model->node_heading;?>
                </th>
                <?php  for($i=0;$i<count($arrColumnHeadings);$i++) {
                        ## Do not show columns that are excluded
                       if(!ArrayHelper::KeyExists($arrColumnHeadings[$i]['data_field_template_id'], $arroutputColumnExclusions)) { 
                    //commented cause exlusion is for nodes ,not for columns?>
                    <th>
                    <?php echo $arrColumnHeadings[$i]['heading'];?>
                    </th>
                    <?php }  
                    }?>
              
             </thead>
             <tbody>
                  <tr>
                    <td></td>
                    <?php  for($i=0;$i<count($arrColumnHeadings);$i++) {  
                        ## Do not show columns that are excluded
                        if(!ArrayHelper::KeyExists($arrColumnHeadings[$i]['data_field_template_id'], $arroutputColumnExclusions)) {
                        ?>
                         <td>
                         <strong><?php echo $arrColumnValues[$i]['value'];?></strong>
                         <?php if($arrColumnValues[$i]['value'] == 'Gross Income Yields') { 
                            echo '<br/> Return on column '.$arrColumnValues[$i]['return_column_id'];
                            }?>
                         </td>
                     <?php }
                     }?>
                  </tr>
                  <?php foreach($arrNodes as $node) { $i=1;?>
                         <tr>
                               <td><?php echo $node['name']; ?></td>
                                <?php foreach($arrColumnHeadings as $col) {
                                    ## Do not show columns that are excluded
                        if(!ArrayHelper::KeyExists($col['data_field_template_id'], $arroutputColumnExclusions)) {
                                    ?>
                                    <td></td>
                                <?php }
                                }?>
                         </tr>
                  <?php } ?>
                  
                  
             </tbody>       
            </table>
        </div>
        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>