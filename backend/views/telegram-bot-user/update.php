<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TelegramBotUser */

$this->title = Yii::t('backend', 'Update Telegram Bot User: {name}', [
    'name' => (isset($model->username)) ? $model->username : (isset($model->first_name)) ? $model->first_name : $model->id,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Telegram Bot Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="telegram-bot-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
