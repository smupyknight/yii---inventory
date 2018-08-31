<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SurveyTemplates */
/* @var $form yii\widgets\ActiveForm */
?>

			<div class="left_panel">
    
     <!-- Start input fields - horizontal form -->
     <div class="panel rounded shadow">
        
        <div class="panel-body no-padding rounded-bottom">
                    <?php $form = ActiveForm::begin([
                       'id' => 'create-product-form',
                        'options' => [
                            'class' => 'form-horizontal',
                            //'enableAjaxValidation' => true
                        ],
                        'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-5">{input}</div>{error}',
                            'labelOptions' => ['class' => 'col-sm-3 control-label']
                        ]
                    ]); ?>
                        <div class="form-body">
                       
                                <div class="form-group">
                                    <?php echo $form->field($dataTemplatemodel, 'heading', ['enableAjaxValidation' => 'true'])->textInput([
                                            'class' => 'form-control rounded ajax',
                                            'maxlength' => true,
                                           'placeholder' => 'heading',
                                    ]);?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->field($dataTemplatemodel, 'field_type',['enableAjaxValidation' => 'true'])->dropDownList(
                                            $arrFieldTypes,[
                                            'class' => 'form-control rounded ajax selectField',
                                            'maxlength' => true,
                                            //'id' => 'field_type'
                                    ]);?>
                                </div>
                                <div class="form-group option-class1" <?php if($dataTemplatemodel->field_type != 'selection') { echo 'style="display:none;"';}?>>
                                    <?php echo $form->field($dataTemplatemodel, 'options', ['enableAjaxValidation' => 'true'])->textInput([
                                            'placeholder' => 'Options',
                                            'class' => 'form-control rounded ajax optionsField',
                                            'maxlength' => true,
                                            'id' => 'options'
                                    ]);?>
                                </div>
                         </div><!-- /.form-body -->
                        
                        <div class="modal-footer">
                                
                                    <?php echo Html::a('Cancel','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]);?>
                                    <?php echo Html::submitButton('Save', ["class" =>"btn btn-success save"]) ?>
                                
                                <div class="clearfix"></div>
                         </div><!-- /.form-footer -->
                       <?php ActiveForm::end();?>
                    </div><!-- /.panel-body -->
     <!--/ End input fields - horizontal form -->
</div><!-- left panel-->  
  <script type="text/javascript">

  // data column field type change 
  //$.noConflict();

  $(document).ready(function() {
    // data column field type change  
  $(".selectField").change(function() {
    var currVal = $(this).val();
    if(currVal == 'selection') {
      $(".option-class1").show();
    } else {
      $(".option-class1").hide();
    }
  });
//// tagsinput for multiple values
   $('.optionsField').tagsInput();

   $('.save').click(function(e) {
      if($(".selectField").val() == 'selection' && $(".optionsField").val() == ''  ) {
        $('.option-class1').after('<div class="help-block" style="color:#a94442;">Options cannot be blank.</div>');
        return false;
      }
   });
  });
  </script> 
 