<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TelegramBotUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="telegram-bot-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_bot')->checkbox() ?>
    <?= $form->field($model, 'spam_allowed')->checkbox() ?>
    <?= $form->field($model, 'is_connected')->checkbox() ?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language_code')->dropDownList(Yii::$app->params['availableLocales']) ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'hash')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
