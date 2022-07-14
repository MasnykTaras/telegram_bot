<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use common\components\telegram\components\tInterface\Button;

class AboutCommand extends UserCommand
{

    protected $name        = 'about';                      // Your command's name
    protected $description = 'View all currensies'; // Your command description
    protected $usage       = '/about';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $chat;
    protected $user;

    public function execute()
    {
        $message    = $this->getMessage();            // Get Message object
        $this->chat = $message->getChat();
        $this->user = $message->getFrom();

        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $message = Yii::t('telegram', 'All commands') . PHP_EOL;
        $message .= Yii::t('telegram', 'Start bot:') . ' ' . '/start' . PHP_EOL;
        $message .= Yii::t('telegram', 'View orders:') . ' ' . '/myorders all' . PHP_EOL;
        $message .= Yii::t('telegram', 'View order by id:') . ' ' . '/myorders view {ID}' . PHP_EOL;
        $message .= Yii::t('telegram', 'View orders page:') . ' ' . '/myorders page {page number}' . PHP_EOL;
        $message .= Yii::t('telegram', 'View all currencies:') . ' ' . '/currency' . PHP_EOL;
        $message            .= Yii::t('telegram', 'View info how verify the card:') . ' ' . '/verify' . PHP_EOL;
        $message .= Yii::t('telegram', 'Connect to 365cash account:') . ' ' . '/connect' . PHP_EOL;
        $message .= Yii::t('telegram', 'Change the language\\Изменить язык:') . ' ' . '/language' . PHP_EOL;
        $data    = [
            'chat_id' => $this->chat->getId(),
            'text'    => $message,
        ];

        return Request::sendMessage($data);        // Send message!
    }

}
