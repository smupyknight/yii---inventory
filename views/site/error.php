<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
//use app\assets\admin\page\ErrorAsset;

//ErrorAsset::register($this);

//$this->title = $this->title;
$this->registerCssFile('@web/css/error-page.css');
?>
<section id="page-content">
<div class="error-wrapper">
    <h1><?= Html::encode($this->title) ?></h1>
    <h4><?= nl2br(Html::encode($message)) ?></h4>
</div>