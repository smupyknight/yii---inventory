<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SurveyTemplatesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default panel-blog rounded shadow">
    <div class="panel-body">
       

    <?php $form = ActiveForm::begin([
	'fieldConfig' => [
							'template' => '{label}<div class="col-sm-2">{input}</div>',
							'labelOptions' => [ 'class' => 'col-sm-1 control-label']
				],
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	 <div class="row">
     <div>
     <?= $form->field($model, 'quarter')->dropDownList($quarters); ?>
    </div>
    <?= $form->field($model, 'publication')->dropDownList($publications, ['prompt' => 'All']); ?>
   
    <?= $form->field($model, 'status')->dropDownList(['' => 'All', '0' => 'Open', '1' => 'Closed']); ?>
    
   <?= Html::submitButton('Filter', ['class' => 'btn btn-success']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>