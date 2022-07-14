<?php

namespace modules\statistic\console;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'modules\statistic\console\controllers';

    public function init()
    {
        parent::init();
    }

    /**
     * @param \yii\i18n\MissingTranslationEvent $event
     */
    public static function missingTranslation($event)
    {
        // do something with missing translation
    }
}
