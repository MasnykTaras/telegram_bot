<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Currency;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Currencies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Currency'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'reserve',
            'code',
            'buy_commission',
        [
            'attribute' => 'status',
            'format'    => 'raw',
            'value'     => function($model) {
                return Html::dropDownList('status', $model->status, Currency::currencyStatus(),
                                ['class'    => 'dual select-status ' . 'status-' . $model->status,
                                    'onchange' => '
                        $.post( "' . Url::toRoute('/currency/update-status') . '", { status: $(this).val(), id: ' . $model->id . ' } )
                            .done(function( data ) {
                                console.log(data);
                            }
                        );
                    '
                                ]
                );
            },
            'filter' => Currency::currencyStatus()
        ],
        //'sell_commission',
            //'apply_commission_on_sell',
            //'apply_commission_on_buy',
            //'status',
            //'symbol',
            //'placeholder',
            //'regular',
            //'iso_code',
            //'card_number',
            //'parent_id',
            //'sell_fields',
            //'buy_fields',
            //'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
