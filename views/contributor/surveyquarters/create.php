<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Surveyquarters */

$this->title = 'Create Surveyquarters';
$this->params['breadcrumbs'][] = ['label' => 'Surveyquarters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="surveyquarters-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
