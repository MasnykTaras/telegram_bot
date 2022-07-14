<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Direction;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DirectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Directions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Direction'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
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
        'rate',
            [
            'attribute' => 'main_currency_name',
            'value'     => function($model) {
                return $model->mainCurrency->name;
            },
        ],
        [
            'attribute' => 'status',
            'format'    => 'raw',
            'value'     => function($model) {
                return Html::dropDownList('status', $model->status, Direction::directionStatus(),
                                ['class'    => 'dual select-status ' . 'status-' . $model->status,
                                    'onchange' => '
                        $.post( "' . Url::toRoute('/direction/update-status') . '", { status: $(this).val(), id: ' . $model->id . ' } )
                            .done(function( data ) {
                                console.log(data);
                            }
                        );
                    '
                                ]
                );
            },
            'filter' => Direction::directionStatus()
        ],
        //'status',
            //'min_sell',
            //'min_buy',
            //'max_sell',
            //'max_buy',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
