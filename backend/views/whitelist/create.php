<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\Whitelist */

$this->title = 'Create Whitelist';
$this->params['breadcrumbs'][] = ['label' => 'Whitelists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="whitelist-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
