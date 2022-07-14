<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'hash') ?>

    <?= $form->field($model, 'order_hash') ?>

    <?= $form->field($model, 'sell_amount') ?>

    <?= $form->field($model, 'buy_amount') ?>

    <?php // echo $form->field($model, 'sell_source') ?>

    <?php // echo $form->field($model, 'buy_target') ?>

    <?php // echo $form->field($model, 'payment_address') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'old_rate') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'sell_currency_id') ?>

    <?php // echo $form->field($model, 'buy_currency_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sub_status') ?>

    <?php // echo $form->field($model, 'main_email') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'user_ip') ?>

    <?php // echo $form->field($model, 'direction_id') ?>

    <?php // echo $form->field($model, 'init_sell_amount') ?>

    <?php // echo $form->field($model, 'init_buy_amount') ?>

    <?php // echo $form->field($model, 'our_buy_amount') ?>

    <?php // echo $form->field($model, 'our_sell_amount') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
