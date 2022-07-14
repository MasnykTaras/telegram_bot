<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="currency-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'reserve',
            'code',
            'buy_commission',
            'sell_commission',
            'apply_commission_on_sell',
            'apply_commission_on_buy',
        [
            'attribute' => 'status',
            'value'    => function($model) {
                return $model->statusName;
            }
        ],
        'symbol',
            'placeholder',
            'regular',
            'iso_code',
            'card_number',
            'parent_id',
            'sell_fields',
            'buy_fields',
            'type',
        ],
    ]) ?>

</div>
