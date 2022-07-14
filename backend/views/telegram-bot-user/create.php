<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TelegramBotUser */

$this->title = Yii::t('backend', 'Create Telegram Bot User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Telegram Bot Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telegram-bot-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
