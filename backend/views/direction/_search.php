<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\DirectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="direction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sell_currency_id') ?>

    <?= $form->field($model, 'buy_currency_id') ?>

    <?= $form->field($model, 'rate') ?>

    <?= $form->field($model, 'main_currency') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'min_sell') ?>

    <?php // echo $form->field($model, 'min_buy') ?>

    <?php // echo $form->field($model, 'max_sell') ?>

    <?php // echo $form->field($model, 'max_buy') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
