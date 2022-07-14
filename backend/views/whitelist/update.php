<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\Whitelist */

$this->title = 'Update Whitelist: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Whitelists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="whitelist-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
