<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="export-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($model, 'table_id')->dropDownList(
            $tables,
        [
            'prompt' => Yii::t('frontend', 'Select'),
        ]
)
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Export'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php if ($json): ?>
    <h3>Export data</h3>
        <div class="form-group">

            <button onclick="copyJson()" class="btn btn-primary">Copy text</button>
            </div>
        <textarea id="new-json" rows="10" cols="100"><?= $json ?></textarea>
    <?php endif; ?>
</div>
<script>
function copyJson() {
  var copyText = document.getElementById("new-json");
    copyText.focus();
    document.execCommand('SelectAll')
    document.execCommand("copy");
}
</script>
