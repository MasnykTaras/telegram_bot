<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\TelegramBotUser;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TelegramBotUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Telegram Bot Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telegram-bot-user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Telegram Bot User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'is_bot',
            'first_name',
            'last_name',
            'username',
        [
            'attribute' => 'spam_allowed',
            'value'     => function($model) {
                return $model->spamStatusName();
            },
            'filter' => TelegramBotUser::spamStatus(),
        ],
        'preferred_language',
        'discount',
        'created_at',
        //'updated_at',
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{sending-message}',
            'buttons'  => [
                'sending-message' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-paper-plane"></i>',
                                    '#',
                                    [
                                        'data'    => [
                                            'toggle'   => 'modal',
                                            'order-id' => $model->id,
                                            'target'   => '#sendMessage'
                                        ],
                                        'title'   => Yii::t('backend', 'Send Message'),
                                        'onclick' => '$("#sendMessage #send-message-form .user-id").val( $(this).data("order-id"));'
                                    ]
                    );
                },
            ],
        ],
        ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<!-- SEND RECIEPT MODAL AREA -->
<div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= Yii::t('backend', 'Send message') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php Pjax::begin(['id' => 'send-message-form-pjax', 'enablePushState' => false]); ?>
                <?= Yii::$app->controller->renderPartial('parts/_send-message-form', ['model' => $messageModel]) ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>