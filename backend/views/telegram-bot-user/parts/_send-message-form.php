<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?= (isset($message)) ? '<div class="alert alert-info" role="alert">' . $message . '</div>' : ''
?>
<?php
$form = ActiveForm::begin([
            'id'      => 'send-message-form',
            'action'  => '/telegram-bot-user/send-message',
            'options' => ['data-pjax' => true]
        ])
?>
<?= $form->field($model, 'id')->hiddenInput(['class' => 'user-id'])->label(false) ?>
<?= $form->field($model, 'message')->textarea(['rows' => 12]) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('backend', 'Send'), ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end() ?>