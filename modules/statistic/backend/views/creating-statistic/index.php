<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel modules\statistic\common\models\search\OrdersCreatingStatisticSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders Creating Statistics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-creating-statistic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('backend', 'Type') ?></th>
                <th><?= Yii::t('backend', 'Start') ?></th>
                <th><?= Yii::t('backend', 'Completed') ?></th>
                <th><?= Yii::t('backend', 'Email') ?></th>
                <th><?= Yii::t('backend', 'Email Verify Start') ?></th>
                <th><?= Yii::t('backend', 'Email Verify End') ?></th>
                <th><?= Yii::t('backend', 'Card') ?></th>
                <th><?= Yii::t('backend', 'Card Verify Start') ?></th>
                <th><?= Yii::t('backend', 'Card Verify End') ?></th>
                <th><?= Yii::t('backend', 'Canceled Preorder') ?></th>
                <th><?= Yii::t('backend', 'Canceled Continued') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= Yii::t('backend', 'Day') ?></td>
                <td><?= $day->start ?></td>
                <td><?= $day->completed ?></td>
                <td><?= $day->email ?></td>
                <td><?= $day->email_verify_start ?></td>
                <td><?= $day->email_verify_end ?></td>
                <td><?= $day->card ?></td>
                <td><?= $day->card_verify_start ?></td>
                <td><?= $day->card_verify_end ?></td>
                <td><?= $day->canceled_preorder ?></td>
                <td><?= $day->canceled_continued ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('backend', 'Month') ?></td>
                <td><?= $month->start ?></td>
                <td><?= $month->completed ?></td>
                <td><?= $month->email ?></td>
                <td><?= $month->email_verify_start ?></td>
                <td><?= $month->email_verify_end ?></td>
                <td><?= $month->card ?></td>
                <td><?= $month->card_verify_start ?></td>
                <td><?= $month->card_verify_end ?></td>
                <td><?= $month->canceled_preorder ?></td>
                <td><?= $month->canceled_continued ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('backend', 'Full Time') ?></td>
                <td><?= $fullTime->start ?></td>
                <td><?= $fullTime->completed ?></td>
                <td><?= $fullTime->email ?></td>
                <td><?= $fullTime->email_verify_start ?></td>
                <td><?= $fullTime->email_verify_end ?></td>
                <td><?= $fullTime->card ?></td>
                <td><?= $fullTime->card_verify_start ?></td>
                <td><?= $fullTime->card_verify_end ?></td>
                <td><?= $fullTime->canceled_preorder ?></td>
                <td><?= $fullTime->canceled_continued ?></td>
            </tr>
        </tbody>
    </table>
    <?php echo $this->render('parts/_graph', ['model' => $day, 'title' => Yii::t('backend', 'Day')]); ?>
    <?php echo $this->render('parts/_graph', ['model' => $month, 'title' => Yii::t('backend', 'Month')]); ?>
    <?php echo $this->render('parts/_graph', ['model' => $fullTime, 'title' => Yii::t('backend', 'Full Time')]); ?>


</div>
