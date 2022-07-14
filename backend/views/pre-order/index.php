<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PreOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Pre Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pre-order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Pre Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'direction_id',
            'main_email:email',
            'sell_amount',
            'sell_wallet',
        'sell_card_first_name',
        'sell_card_last_name',
        'sell_phone',
        'buy_wallet',
        'buy_card_first_name',
        'buy_card_middle_name',
        'buy_card_last_name',
        'status',
        'created_at:datetime',
        'updated_at:datetime',
        'rate',
        'chat_id',
        'buy_phone',
        ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
