<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\components\telegram\TelegramBot;

class TelegramBotController extends Controller
{

    public $enableCsrfValidation = false;
    private static $botApiKey;
    private static $botUsername;
//149.154.160.0/20 and 91.108.4.0/22

    public function actionRun($token)
    {
        if (env('WEBHOOK_URL_TOKEN') != $token) {
            throw new NotFoundHttpException('Not Found');
        }
        $content = file_get_contents("php://input");
//        Yii::warning($content, 'webhook_request');
        $update  = json_decode($content, true);


        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot               = new TelegramBot(self::$botApiKey, self::$botUsername);

        $result = $bot->getHoolUpdates();

        return 200;
    }

}
