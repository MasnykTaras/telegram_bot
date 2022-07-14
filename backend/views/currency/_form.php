<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="currency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reserve')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'precision')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'buy_commission')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_commission')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_commission_on_sell')->textInput() ?>

    <?= $form->field($model, 'apply_commission_on_buy')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList($model->currencyStatus()) ?>

    <?= $form->field($model, 'symbol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'placeholder')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'regular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iso_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'card_validation')->dropDownList($model->currencyStatus()) ?>
    <?= $form->field($model, 'email_validation')->dropDownList($model->currencyStatus()) ?>
    <?= $form->field($model, 'ip_validation')->dropDownList($model->currencyStatus()) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
