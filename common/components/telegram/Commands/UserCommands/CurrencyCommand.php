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


class CurrencyCommand extends UserCommand
{

    protected $name        = 'currency';                      // Your command's name
    protected $description = 'View all currensies'; // Your command description
    protected $usage       = '/currency';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $chat;
    protected $user;

    public function execute()
    {
        
        // Command triggered with callback
        if ($this->getCallbackQuery()) {
            return $this->executeCallback();
        }

        // Command triggered with regular message
        return $this->executeMessage();
    }

    public function executeCallback()
    {
        // Explode query_data into chunks
        $callback = $this->getCallbackQuery();
        $message  = $callback->getMessage();
        $this->chat         = $message->getChat();
        $this->user         = $callback->getFrom();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $data['chat_id']    = $this->chat->getId();

        $query_data = explode('_', $callback->getData());
        
        // Do not forget to answer callback query here!
        Request::answerCallbackQuery(['callback_query_id' => $this->getUpdate()->getCallbackQuery()->getId()]);
       
        // Do something, like running different logic depending on first parameter, for example
        switch ($query_data[1]) {
            case 'get':
                if ($keyboard = Button::GetDirection($query_data[2], $this->user->getId())) {
                    $keyboard->setResizeKeyboard(true);
                    $message              = Yii::t('telegram', 'You give: {currency_name}.', ['currency_name' => $query_data[2]]) . PHP_EOL . Yii::t('telegram', 'Now indicate the currency you want to receive');
                    $data['reply_markup'] = $keyboard;
                } else {
                    $message = Yii::t('telegram', 'There are currently no active destinations for this currency or not enough reserve.');
                }
                break;
            default:
                $message = Yii::t('telegram', 'Not correct request');
                break;
        }
        
        
        $data ['text'] = $message;


        return Request::sendMessage($data);
    }
    public function executeMessage()
    {
        $message = $this->getMessage();            // Get Message object
        $this->chat = $message->getChat();
        $this->user = $message->getFrom();

        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        if ($keyboard = Button::GetCurrency()) {
            $keyboard->setResizeKeyboard(true);
        }
        $data = [
            'chat_id'      => $this->chat->getId(),
            'text'         => Yii::t('telegram', 'Choose the currency you want to exchange'),
            'reply_markup' => $keyboard,
        ];

        return Request::sendMessage($data);
    }

}
