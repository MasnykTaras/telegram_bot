<?php

namespace modules\statistic\backend;

use Yii;

use yii\i18n\PhpMessageSource;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'modules\statistic\backend\controllers';

    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'modules\statistic\commands';
        }
    }

}
