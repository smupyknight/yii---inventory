<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OutputTablesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="output-tables-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'heading') ?>

    <?= $form->field($model, 'sub_heading') ?>

    <?= $form->field($model, 'output_column') ?>

    <?= $form->field($model, 'survey_template_question_id') ?>

    <?php // echo $form->field($model, 'first_field_id') ?>

    <?php // echo $form->field($model, 'last_field_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'node_heading') ?>

    <?php // echo $form->field($model, 'contributor_code') ?>

    <?php // echo $form->field($model, 'difference_presentation') ?>

    <?php // echo $form->field($model, 'parent_node_visibility') ?>

    <?php // echo $form->field($model, 'sd') ?>

    <?php // echo $form->field($model, 'a_b_r') ?>

    <?php // echo $form->field($model, 'show_parent_average') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
