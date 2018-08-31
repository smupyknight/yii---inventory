<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\OutputTables */

$this->title = 'Create Output Tables';
?>
<section id="page-content">


    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-table"></i> <?php echo Html::encode($this->title);?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                      <?php echo Html::a('Dashboard', ['site/index']);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Surveys',Url::previous('survey'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Questions', Url::previous('question'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Output Tables', Url::previous('outputtable'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Create</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <?= $this->render('_form', [
        'model' => $model,
        'arrNodes' => $arrNodes,
        'arrColumns' => $arrColumns,
        'arrGrossColumn' => $arrGrossColumn,
        'arrDifference' => $arrDifference,
        'intQuestionId' => $intQuestionId
    ]) ?>
