<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LocationNodes */

$this->title = 'Update Parent Node: ' . ' ' . $model->name;
?>
<section id="page-content">
	<!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-map-marker"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                      <?php echo Html::a('Dashboard', ['site/index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Parent Nodes', ['index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Update</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
