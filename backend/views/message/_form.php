<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>
    <ul>
        <li><?= Yii::t('backend', 'Dynamic values')?></li>
        <li>{username} - <?= Yii::t('common', 'Username')?></li>
    </ul>
    <?= $form->field($model, 'message')->textarea(['rows' => 12]) ?>

    <?= $form->field($model, 'language')->dropDownList(Yii::$app->params['availableLocales']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
