<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Currency;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\BlackList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="black-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($model, 'currency_id')->dropDownList(
            ArrayHelper::map(Currency::find()->all(), 'id', 'name'),
            [
                'prompt' => Yii::t('frontend', 'Select'),
            ]
    )
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
