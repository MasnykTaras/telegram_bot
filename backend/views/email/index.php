<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Email;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Emails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Email'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'email:email',
            [
            'attribute' => 'status',
            'format'    => 'raw',
            'value'     => function($model) {
                return Html::dropDownList('status', $model->status, Email::emailStatus(),
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
            'filter' => Email::emailStatus()
        ],
        'created_at:datetime',
        'updated_at:datetime',
        //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
