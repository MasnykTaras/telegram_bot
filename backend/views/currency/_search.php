<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\CurrencySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="currency-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'reserve') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'buy_commission') ?>

    <?php // echo $form->field($model, 'sell_commission') ?>

    <?php // echo $form->field($model, 'apply_commission_on_sell') ?>

    <?php // echo $form->field($model, 'apply_commission_on_buy') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'symbol') ?>

    <?php // echo $form->field($model, 'placeholder') ?>

    <?php // echo $form->field($model, 'regular') ?>

    <?php // echo $form->field($model, 'iso_code') ?>

    <?php // echo $form->field($model, 'card_number') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'sell_fields') ?>

    <?php // echo $form->field($model, 'buy_fields') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
