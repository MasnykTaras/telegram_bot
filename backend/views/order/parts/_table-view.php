<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="order-view">
    <p class="well">
        Клиенту:<br>
        Status page 365cach:  <a href="<?= $model->statusLink ?>"> Cklick</a>
        <?php if (!empty($model->payment_address)): ?>
            <?= $model->getAttributeLabel('payment_address') ?> <b><?= $model->payment_address ?></b><br>
        <?php endif; ?>
            <?php if (!empty($model->sell_link)): ?>
            <b><?= $model->getAttributeLabel('sell_link') ?></b> <a href="<?= $model->sell_link ?>" target="_blink"><?= $model->sell_link ?></a><br>
            <?php endif; ?>


    </p>
    <table id="w0" class="table table-striped table-bordered detail-view">
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('id') ?></th>
            <td><?= $model->id ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('hash') ?></th>
            <td><?= $model->hash ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('order_hash') ?></th>
            <td><?= $model->order_hash ?></td>
        </tr>
        <tr>
            <th><?= Yii::t('backend', 'Auth Code') ?></th>
            <td></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('user_id') ?></th>
            <td><?= $model->user_id ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('main_email') ?></th>
            <td><?= $model->main_email ?></td>
        </tr>
        <tr>
            <th><?= Yii::t('backend', 'User Language') ?></th>
            <td><?= $model->user->language ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('sell_currency_name') ?></th>
            <td><?= $model->sellCurrency->name ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('sell_amount') ?></th>
            <td><?= $model->sell_amount ?></td>
        </tr>

        <tr>
            <th><?= $model->getAttributeLabel('buy_currency_name') ?></th>
            <td><?= $model->buyCurrency->name ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('buy_amount') ?></th>
            <td><?= $model->buy_amount ?> <?= (isset($model->commission) && $model->commission > 0) ? ' + ' . $model->commission : '' ?></td>
        </tr>

        <tr>
            <th><?= $model->getAttributeLabel('sell_source') ?></th>
            <td><?= $model->sell_source ?></td>
        </tr>        
        <tr>
            <th><?= $model->getAttributeLabel('buy_target') ?></th>
            <td><?= $model->buy_target ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('wallet_in') ?></th>
            <td><?= $model->wallet_in ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('pay_url') ?></th>
            <td><p style="word-break: break-all"><?= $model->pay_url ?></p></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('rate') ?></th>
            <td><?= $model->rate ?></td>
        </tr>       
        <tr>
            <th><?= $model->getAttributeLabel('status') ?></th>
            <td><?= $model->statusName ?></td>
        </tr>

        <tr>
            <th><?= Yii::t('backend', 'Paid Received At') ?></th>
            <td></td>
        </tr>

        <tr>
            <th><?= $model->getAttributeLabel('created_at') ?></th>
            <td><?= Yii::$app->formatter->format($model->created_at, 'datetime') ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('updated_at') ?></th>
            <td><?= Yii::$app->formatter->format($model->updated_at, 'datetime') ?></td>
        </tr>
    </table>
</div>