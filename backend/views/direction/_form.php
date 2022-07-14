<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Currency;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Direction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="direction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($model, 'sell_currency_id')->dropDownList(
            ArrayHelper::map(Currency::getAllCurrencies(), 'id', 'name'),
            [
                'prompt'   => Yii::t('frontend', 'Select'),
                'onchange' => '
                        $( "#main_currency" ).hide();
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
            ArrayHelper::map(Currency::getAllCurrencies(), 'id', 'name'),
            [
                'prompt'   => Yii::t('frontend', 'Select'),
                'onchange' => '
                      $( "#main_currency" ).show();
                        $( "#' . Html::getInputId($model, 'main_currency') . '" ).html($( "#' . Html::getInputId($model, 'sell_currency_id') . ' option:selected" ).clone());
                        $( "#' . Html::getInputId($model, 'main_currency') . '" ).append($( "#' . Html::getInputId($model, 'buy_currency_id') . ' option:selected" ).clone());
                    '
            ]
    )
    ?>
    <div id="main_currency" style="<?= (empty($model->main_currency)) ? 'display:none' : '' ?>">
    <?php echo $form->field($model, 'main_currency')->dropDownList(ArrayHelper::map(Currency::getAllCurrencies(), 'id', 'name')) ?>
    </div>
<?= $form->field($model, 'status')->dropDownList($model->directionStatus()) ?>
<?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_sell')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_buy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_sell')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_buy')->textInput(['maxlength' => true]) ?>
    <?=
    $form->field($model, 'direction_fields')->widget(
            'trntv\aceeditor\AceEditor',
            [
                'mode'  => 'json', // programing language mode. Default "html"
                'theme' => 'github', // editor theme. Default "github"
            ]
    )
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
