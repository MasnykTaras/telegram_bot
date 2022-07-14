<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Message;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Message'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'message',
            [
                'attribute' => 'is_sent',
                'value' => function($model){
                    return $model->sendStatusName();
                },
                'filter' => Message::sendStatus(),
            ],
            [
                'attribute' => 'language',
                'filter' => Yii::$app->params['availableLocales'],
            ],
            [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{sending-message}',
            'buttons'  => [
                'sending-message' => function ($url, $model, $key) {
                    
                  return Html::a('<i class="fa fa-paper-plane"></i>',
                                    '#',
                                    [
                                        'title'   => Yii::t('backend', 'Send Message'),
                                        'onclick' => '$.post( "' . Url::toRoute('/message/send-message') . '", {id: ' . $model->id . ' } )
                                                        .done(function( data ) {
                                                            console.log(data);
                                                        }
                                                    );'
                                    ]
                            );
                },
            ]
        ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
