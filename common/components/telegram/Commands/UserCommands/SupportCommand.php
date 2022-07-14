<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use yii\helpers\Html;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use common\components\telegram\components\tInterface\Button;

class SupportCommand extends UserCommand
{

    protected $name        = 'support';                      // Your command's name
    protected $description = 'A command for test'; // Your command description
    protected $usage       = '/support';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $chat;
    protected $user;

    public function execute()
    {
        $message  = $this->getMessage();            // Get Message object
        $this->chat = $message->getChat();
        $this->user         = $message->getFrom();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $keyboard = Button::StartBtn();

        $keyboard->setResizeKeyboard(true);

        $data = [
            'chat_id'      => $this->chat->getId(),
            'text'         => Yii::t('telegram', 'If you have any problem, please connect us {email}', ['email' => env('ADMIN_EMAIL')]),
            'reply_markup' => $keyboard,
        ];

        return Request::sendMessage($data);        // Send message!
    }

}
