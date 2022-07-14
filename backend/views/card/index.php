<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Card;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Cards');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Card'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'card_number',
             [
            'attribute' => 'status',
            'format'    => 'raw',
            'value'     => function($model) {
                return Html::dropDownList('status', $model->status, Card::cardStatus(),
                                ['class'    => 'dual select-status ' . 'status-' . $model->status,
                                    'onchange' => '
                        $.post( "' . Url::toRoute('/card/update-status') . '", { status: $(this).val(), id: ' . $model->id . ' } )
                            .done(function( data ) {
                                console.log(data);
                            }
                        );
                    '
                                ]
                );
            },
            'filter' => Card::cardStatus()
        ],
        'system',
        'code',
        'created_at:datetime',
        //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
