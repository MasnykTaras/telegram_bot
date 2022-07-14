<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PreOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pre-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'direction_id')->textInput() ?>

    <?= $form->field($model, 'main_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_wallet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_card_first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_card_last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_wallet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_card_first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_card_middle_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_card_last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chat_id')->textInput() ?>

    <?= $form->field($model, 'buy_phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
