<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="export-form">
    <?= ($result) ? '<h3>' . Yii::t('backend', 'Column was add ') . $result . '</h3>' : '' ?>
    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($model, 'table_id')->dropDownList(
            $tables,
            [
                'prompt' => Yii::t('frontend', 'Select'),
            ]
    )
    ?>
    <?php
    echo $form->field($model, 'json')->textarea(['rows' => 10])
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Import'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

