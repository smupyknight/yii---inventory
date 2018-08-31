<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Surveytemplatequestions */

$this->title = 'Create Surveytemplatequestions';
$this->params['breadcrumbs'][] = ['label' => 'Surveytemplatequestions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="surveytemplatequestions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
