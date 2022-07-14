<?php

namespace common\components\telegram;

use Yii;
use yii\helpers\ArrayHelper;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\behaviors\TimestampBehavior;

class TelegramBot
{

    public $botApiKey;
    public $botUsername;
    // Define all paths for your custom commands in this array (leave as empty array if not used)
    public $hookUrl;
    public $commandsPaths = [
        __DIR__ . '/Commands',
    ];
    public $mySql         = [
        'host',
        'user',
        'password',
        'database'
    ];
    
    public $tablePrefix   = 'telegram_bot_';

    public function __construct($apiKey, $username)
    {
        $this->mySql['host']     = env('DB_HOST');
        $this->mySql['user']     = env('DB_USERNAME');
        $this->mySql['password'] = env('DB_PASSWORD');
        $this->mySql['database'] = env('DB_TABLE_NAME');
        $this->botApiKey   = $apiKey;
        $this->botUsername = $username;
        $this->hookUrl           = env('HTTPS_HOST_INFO') . '/telegram-bot/run?token=' . env('WEBHOOK_URL_TOKEN');
        $this->bot         = new Telegram($this->botApiKey, $this->botUsername);
        $this->bot->addCommandsPaths($this->commandsPaths);
        $this->bot->enableMySql($this->mySql, $this->tablePrefix);
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    public function init()
    {
        return true;
    }
    public function setWebhook()
    {
        return $this->bot->setWebhook(urldecode($this->hookUrl), ['certificate' => '/etc/nginx/ssl/bundle2.crt']);
    }

    public function infoWebhook()
    {
        return Request::getWebhookInfo();
    }

    public function deleteWebhook()
    {
       return $this->bot->deleteWebhook();
    }

    public function getCommandsList()
    {
        print_r($this->bot->getCommandsList());
    }

    public function getUpdates()
    {
        print_r($this->bot->handleGetUpdates());
    }

    public function getHoolUpdates()
    {
        print_r($this->bot->handle());
    }

    public function executeCommand($command)
    {
        print_r($this->bot->executeCommand($command));
    }

}
