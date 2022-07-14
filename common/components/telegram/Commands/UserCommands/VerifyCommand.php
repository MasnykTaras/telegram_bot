<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use common\models\WidgetText;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use common\components\telegram\components\tInterface\Button;
use modules\statistic\common\models\OrdersCreatingStatistic;

class VerifyCommand extends UserCommand
{

    protected $name        = 'verify';                      // Your command's name
    protected $description = 'View all currensies'; // Your command description
    protected $usage       = '/verify';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $chat;
    protected $user;

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object
        $this->chat = $message->getChat();
        $this->user         = $message->getFrom();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        OrdersCreatingStatistic::updateDayCounters(['card_verify_start' => 1]);
        $message = Yii::t('telegram', 'Information') . PHP_EOL;
        $message            .= WidgetText::getText('card-message-validate', $this->user->getID());
        $data    = [
            'chat_id' => $this->chat->getId(),
            'text'    => $message,
        ];

        return Request::sendMessage($data);        // Send message!
    }

}
