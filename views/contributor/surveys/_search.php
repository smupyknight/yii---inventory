<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Surveyssearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-body">
	<div class="row">
        <div class="col-md-12 col-md-offset-2">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
		'fieldConfig' => [
							'template' => '{label}<div class="col-sm-2">{input}</div>',
							'labelOptions' => [ 'class' => 'col-sm-1 control-label']
				],
    ]); ?>
	<?= $form->field($model, 'quarter')->dropDownList($quarters); ?>
    <?= $form->field($model, 'completed')->dropDownList([''=>'All','1'=>'Completed','0'=>'Not Completed'])->label('Status'); ?>
    
    <?php echo Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
    <?php echo Html::a('Reset', ['index'], ['class' => 'btn btn-default']); ?>
        
       
   <!-- </div>-->

    <?php ActiveForm::end(); ?>
		</div>
        </div>
</div>
