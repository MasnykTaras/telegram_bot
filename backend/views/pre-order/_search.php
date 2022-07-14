<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PreOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pre-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'direction_id') ?>

    <?= $form->field($model, 'main_email') ?>

    <?= $form->field($model, 'sell_amount') ?>

    <?php // echo $form->field($model, 'sell_wallet') ?>

    <?php // echo $form->field($model, 'sell_card_first_name') ?>

    <?php // echo $form->field($model, 'sell_card_last_name') ?>

    <?php // echo $form->field($model, 'sell_phone') ?>

    <?php // echo $form->field($model, 'buy_wallet') ?>

    <?php // echo $form->field($model, 'buy_card_first_name') ?>

    <?php // echo $form->field($model, 'buy_card_middle_name') ?>

    <?php // echo $form->field($model, 'buy_card_last_name') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'chat_id') ?>

    <?php // echo $form->field($model, 'buy_phone') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
