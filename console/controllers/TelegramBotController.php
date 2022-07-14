<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\telegram\TelegramBot;
use common\components\telegram\SetHook;
use common\components\telegram\NewHook;

class TelegramBotController extends Controller
{
// php console/yii telegram-bot/get-updates
//    public $botApiKey   = '1050767620:AAEUZ8mO21_SZBUJgNo0g8y3-Py3iqqmOsw';
//    public $botUsername = 'Official365CashBot';

    private static $botApiKey;
    private static $botUsername;

    public function actionInit()
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot = new TelegramBot(self::$botApiKey, self::$botUsername);
        return $bot->init();
    }
    public function actionSetWebhook()
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot = new TelegramBot(self::$botApiKey, self::$botUsername);
        return $bot->setWebhook();
    }

    public function actionUnsetHook()
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot               = new TelegramBot(self::$botApiKey, self::$botUsername);
        return $bot->deleteWebhook();
    }

    public function actionGetInfoWebhook()
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot = new TelegramBot(self::$botApiKey, self::$botUsername);
        return $bot->infoWebhook();
    }
    public function actionGetCommandsList()
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot               = new TelegramBot(self::$botApiKey, self::$botUsername);
        return $bot->getCommandsList();
    }

    public function actionGetUpdates()
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot = new TelegramBot(self::$botApiKey, self::$botUsername);
        while (true) {
            $result = $bot->getUpdates();
            sleep(5);
        }
    }
    public function actionExecuteCommand($command)
    {
        self::$botApiKey   = env('BOT_API_KEY');
        self::$botUsername = env('BOT_USER_NAME');
        $bot = new TelegramBot(self::$botApiKey, self::$botUsername);
        return $bot->executeCommand($command);
    }

}
