<?php

use common\models\Order;
use common\models\Whitelist;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\WhitelistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IP White list';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="whitelist-index">

    <p>
        <?php echo Html::a('Add IP', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'ip',
            'name',
            [
                'class' => DataColumn::className(),
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {

                    return $model->getTypeLabel();
                },
                'filter' =>Whitelist::types()

            ],


            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
