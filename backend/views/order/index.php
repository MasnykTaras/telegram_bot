<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= Html::a(Yii::t('backend', 'Reset filters'), Url::to('index'), ['class' => 'btn btn-default']) ?>
    <?= Html::a('<i class="glyphicon glyphicon-refresh"><span class="count"></span></i>', Url::to('index'), ['class' => 'btn btn-default']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
    'tableOptions' => [
        'id'    => 'order-table',
        'class' => 'table table-striped table-bordered'
    ],
    'rowOptions'   => function($model) {
        return ['class' => 'parent-row status-' . $model->status, 'data' => ['updated-at' => $model->updated_at]];
    },
    'columns'                      => [
        [
            'attribute' => 'id',
            'format'    => 'raw',
            'value'     => function($model) {
                return Html::a($model->id,
                                '#',
                                [
                                    'class' => 'order-user-id',
                                    'data'  => [
                                        'toggle'   => 'modal',
                                        'order-id' => $model->id,
                                        'target'   => '#myModal'
                                    ],
                                    'title' => $model->id
                                ]
                )
                        . Html::a('<i class="fa fa-refresh"></i>',
                                '#',
                                [
                                    'class'   => 'order-refresh',
                                    'onclick' => '$.post( "' . Url::toRoute('/order/check-order-info') . '", {order_hash: "' . $model->order_hash . '"})
                                        .done(function( data ) {
                                            console.log(data);
                                        }
                                    );',
                                    'title'   => Yii::t('backend', 'Refresh')
                                ]
                );
            }
        ],
        'main_email',
        [
            'attribute' => 'sell_currency_name',
            'value'     => function($model) {
                return $model->sellCurrency->name;
            },
        ],
        'sell_amount',
        [
            'attribute' => 'buy_currency_name',
            'value'     => function($model) {
                return $model->buyCurrency->name;
            },
        ],
        [
            'attribute' => 'buy_amount',
            'format'    => 'raw',
            'value'     => function($model) {
                $view = "<span>" . (float) $model->buy_amount . '</span><br>';
                $view .= ($model->buy_amount < $model->init_buy_amount) ? "<span class='init-amount'><del>" . (float) $model->init_buy_amount . '</del></span>' : '';
                return $view;
            },
        ],
        [
            'attribute' => 'sell_source',
            'value'     => function($model) {
                if (!empty($model->payment_address)) {
                    return $model->payment_address;
                }
                return $model->sell_source;
            }
        ],
        'buy_target',
        [
            'attribute' => 'rate',
            'format'    => 'raw',
            'value'     => function($model) {
                $view = "<span>" . (float) $model->rate . '</span><br>';
                $view .= ($model->rate != $model->old_rate && $model->old_rate != 0) ? "<span class='init-amount'><del>" . (float) $model->old_rate . '</del></span><br>' : '';
                $view .= "<span class='init-amount'>" . (float) $model->direction->rate . '</span>';
                return $view;
            }
        ],
        [
            'attribute' => 'status',
            'format'    => 'raw',
            'value'     => function($model) {
                return Html::dropDownList('status', $model->status, Order::typeStatus(),
                                ['class'    => 'select-status ' . 'status-' . $model->status,
                                    'onchange' => '
                        $.post( "' . Url::toRoute('/order/update-status') . '", {type:0, status: $(this).val(), id: ' . $model->id . ' } )
                            .done(function( data ) {
                                console.log(data);
                            }
                        );
                    '
                                ]
                );
            },
            'filter' => Order::typeStatus()
        ],
        'created_at:datetime',
        ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<!-- VIEW ORDER INFO MODAL AREA -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= Yii::t('backend', 'View order') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>