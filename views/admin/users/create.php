<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Rodeusers */

$this->title = 'Create Rodeusers';
$this->params['breadcrumbs'][] = ['label' => 'Rodeusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rodeusers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
