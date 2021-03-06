<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\Whitelist */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Whitelists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="whitelist-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ip',
            'name',
        ],
    ]) ?>

</div>
