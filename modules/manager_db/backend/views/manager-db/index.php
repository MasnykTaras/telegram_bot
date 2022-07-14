<?php

use yii\helpers\Url;

$this->title                   = Yii::t('statistic', 'Manager DB');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <div class="manager-index">
            <p><a class="btn btn-primary" href="<?= Url::to(['export']) ?>">Export</a>    </p>
            <p><a class="btn btn-success" href="<?= Url::to(['import']) ?>">Import</a>    </p>
        </div>
    </div>
</div>