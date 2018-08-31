<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\Survey;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\OutputTables */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="body-content animated fadeIn">
     <div class="row">
        <div class="col-md-12">
        <?php echo $surveyDetails=(Survey::widget(['id' => $intQuestionId, 'type' => 'Question']));?>
    
     <!-- Start input fields - horizontal form -->
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
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
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                       /* 'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
                            'labelOptions' => ['class' => 'col-sm-2 control-label']
                        ]*/
                    ]); ?>
<div class="form-group"> 
    <div class="row">
        <div class="col-sm-6">
        <label>Heading <span class="require">*</span></label>
            <?php echo $form->field($model, 'heading')
            ->textInput(['maxlength' => true])
            ->label(false); ?>
        </div>
        <div class="col-sm-6">
        <label>Sub Heading <span class="require">*</span></label>
            <?php echo $form->field($model, 'sub_heading')
            ->textInput(['maxlength' => true])
            ->label(false); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
         <label>Standard Deviation Limit</label>
            <?php echo $form->field($model, 'sd')
            ->textInput()
            ->label(false); ?>
        </div>
         <div class="col-sm-6">
         <label>Parent Node Visibility</label>
            <?php echo $form->field($model, 'parent_node_visibility')
            ->radioList(['text' => 'text' , 'dividing line' => 'dividing line', 'hidden' => 'hidden'])
            ->label(false); ?>
        </div>
        
        
    </div>
    <div class="row">
        <div class="col-sm-6">
        <label></label>
            <?php echo $form->field($model, 'a_b_r')
            ->checkbox(array('label'=>'Show a , b , r Values?'))
            ->label(false); ?>
        </div>
        <div class="col-sm-6">
        <label></label>
            <?php echo $form->field($model, 'contributor_code')
            ->checkbox([ 'label' => 'Show Contributor Codes?'])
            ->label(false); ?>
        </div>
    </div>
     <div class="row">
         <div class="col-sm-6">
         <?php echo $form->field($model, 'show_parent_average')
            ->checkbox([ 'label' => 'Show Parent Average?'])
            ->label(false); ?>
         </div>
         <div class="col-sm-6">
        <label>Additional Column?</label>
            <?php echo $form->field($model, 'output_column')
            ->dropDownList($model->additionalColumns($arrDifference))
            ->label(false); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-11">
        <div class="table-responsive ">
            <table class="table table-success table-condensed table-bordered">
            <thead>
                <th><?php echo $form->field($model, 'node_heading')
                    ->textInput(['value' => 'Area'])
                    ->label(false);?>
                </th>
                <?php  for($i=0;$i<count($arrColumns);$i++) { ?>
                    <th>
                    <?php echo $form->field($model, 'column_heading[]')
                    ->textInput(['value' => $model->isNewRecord ? $arrColumns[$i]['heading'] : $model->column_heading[$i]])
                    ->label(false);?>
                    </th>
                    <?php } ?>
              
             </thead>
             <tbody>
                  <tr>
                    <td></td>
                    <?php  for($i=0;$i<count($arrColumns);$i++) {  ?>
                         <td>
                         <?php //echo $form->field($model, 'value[]')
                        //->dropDownList($model->fieldFilters(), [ 'class' => '_filters form-control', 'data-retColumn' => $col['id'] ])
                        //->label(false); 
                         echo Html::dropDownList('value[]',!$model->isNewRecord ? $model->value[$i] : null, $model->fieldFilters(),
                            [ 'class' => '_filters form-control', 'data-retColumn' => $arrColumns[$i]['id']]);
                         ?>
                        

                        <span <?php echo ($model->isNewRecord || $model->value[$i] !="Gross Income Yields")  ? 'class="hideOption"': '' ;?> id="return_column<?php echo $arrColumns[$i]['id'];?>">
                        <?php $id = $arrColumns[$i]['id'];
                        $arr_GrossColumn    = array();
                        $arr_GrossColumn[$id] =$arrGrossColumn;
                       if (($arrColumns[$i]['order'] = array_search($arrColumns[$i]['order'], $arr_GrossColumn[$id])) !== false) {
                            unset($arr_GrossColumn[$id][$arrColumns[$i]['order']]);
                        } ?>
                             <?php //echo $form->field($model, 'return_column[]')
                                //->dropDownList($arr_GrossColumn[$id])
                                //->label(false);
                             echo Html::dropDownList(
                                    'return_column[]',
                                    !$model->isNewRecord ? $model->return_column[$i] : null, 
                                    $arr_GrossColumn[$id],
                                    ['class' => 'form-control']
                                    );
                                 ?>
                        </span>
                        </td>
                     <?php }?>
                  </tr>
                  <?php foreach($arrNodes as $node) { $i=1;?>
                         <tr>
                               <td><?php echo $node['name']; ?></td>
                                <?php foreach($arrColumns as $col) {?>
                                    <td></td>
                                <?php }?>
                         </tr>
                  <?php } ?>
                  <tr>
                    <td> <strong>Hide Column ? </strong></td>
                    <?php for($i=0;$i<count($arrColumns);$i++) {?>
                        <td>

                       <?php echo Html::checkBox('hide_column[]',!$model->isNewRecord ? $model->hide_column[$i] : false, [ 'value' => $arrColumns[$i]['id']]);?></td>
           
                    <?php }?>

                  </tr>
                  
             </tbody>       
            </table>
        </div>
        </div>
        <div class="col-sm-1">
        <span id="column-n" <?php echo ($model->isNewRecord || $model->output_column !="n")  ? 'class="hideOption"': '' ;?>><strong>n</strong></span>
        <span id="column-difference" <?php echo ($model->isNewRecord || $model->output_column !="Difference")  ? 'class="hideOption"': '' ;?>>
            <strong>Difference between columns</strong>
            <br/>
             <?php echo $form->field($model, 'difference_presentation')
                                ->dropDownList($arrDifference)
                                ->label(false); ?>
        </span>
        </div>
    </div>
</div><!-- /.panel-body -->
    <div class="form-footer">
            <div class="pull-right">
                <?php echo Html::a('Cancel', Url::previous('outputtable'), ['class' => 'btn btn-danger mr-5']);?>
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']);?>
            </div>
            <div class="clearfix"></div>
    </div><!-- /.form-footer -->

      <?php ActiveForm::end();?>
                    </div><!-- /.panel-body -->
                </div><!-- /.panel -->
     <!--/ End input fields - horizontal form -->

   
        </div>
    </div>
</div>
