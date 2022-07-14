<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

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
            'hash',
            'order_hash',
            'sell_amount',
        'buy_amount',
            'sell_source',
            'buy_target',
        'payment_address',
            'rate',
            'old_rate',
            'created_at:datetime',
        'updated_at:datetime',
         [
            'attribute' => 'sell_currency_name',
            'value'     => function($model) {
                return $model->sellCurrency->name;
            },
        ],
        [
            'attribute' => 'buy_currency_name',
            'value'     => function($model) {
                return $model->buyCurrency->name;
            },
        ],
        [
            'attribute' => 'status',
            'value'     => function($model) {
                return $model->statusName;
            },
        ],
        'main_email:email',
        'user_id',
        'user_ip',
        'init_sell_amount',
        'init_buy_amount',
        'our_buy_amount',
        'our_sell_amount',
    ],
    ]) ?>

</div>
