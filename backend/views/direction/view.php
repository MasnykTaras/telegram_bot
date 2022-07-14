<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Direction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Directions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="direction-view">

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
            [
            'attribute' => 'sell_currency_id',
            'value'     => function($model) {
                return $model->sellCurrency->name;
            },
        ],
        [
            'attribute' => 'buy_currency_id',
            'value'     => function($model) {
                return $model->buyCurrency->name;
            },
        ],
        [
            'attribute' => 'main_currency',
            'value'     => function($model) {
                return $model->mainCurrency->name;
            },
        ],
        'rate',
        [
            'attribute' => 'status',
            'format'    => 'html',
            'value'     => function($model) {
                return Html::tag('p', $model->getStatusName($model->status), ['class' => 'status status-' . $model->status]);
            }
        ],
        'rate',
        'min_sell',
            'min_buy',
            'max_sell',
            'max_buy',
        ],
    ]) ?>

</div>
