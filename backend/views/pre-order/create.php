<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PreOrder */

$this->title = Yii::t('backend', 'Create Pre Order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pre Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pre-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
