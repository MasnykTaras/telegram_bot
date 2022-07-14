<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Currency;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_target')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'old_rate')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($model, 'sell_currency_id')->dropDownList(
            ArrayHelper::map(Currency::getAllCurrencies(), 'id', 'name'),
            [
                'prompt'   => Yii::t('frontend', 'Select'),
                'onchange' => '
                        $.get( "' . Url::toRoute('/direction/currency-list') . '", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#' . Html::getInputId($model, 'buy_currency_id') . '" ).html( data );
                            }
                        );
                    '
            ]
    )
    ?>
    <?php
    echo $form->field($model, 'buy_currency_id')->dropDownList(
            ArrayHelper::map(Currency::getAllCurrencies(), 'id', 'name')
    )
    ?>


<?= $form->field($model, 'status')->dropDownList($model::typeStatus()) ?>

    <?= $form->field($model, 'sub_status')->textInput() ?>

    <?= $form->field($model, 'main_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'user_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direction_id')->textInput() ?>

    <?= $form->field($model, 'init_sell_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'init_buy_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'our_buy_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'our_sell_amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
