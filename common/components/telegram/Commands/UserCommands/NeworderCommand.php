<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use common\models\PreOrder;
use yii\helpers\ArrayHelper;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\ConversationDB;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use common\components\telegram\components\tInterface\Button;

class NeworderCommand extends UserCommand
{

    protected $name        = 'neworder';                      // Your command's name
    protected $description = 'View all currensies'; // Your command description
    protected $usage       = '/neworder';                    // Usage of your command
    protected $version     = '1.0.0';
    protected $user;
    protected $chat; // Version of your command

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
        $callback = $this->getCallbackQuery();
        $message    = $callback->getMessage();
        $this->user = $callback->getFrom();
        $this->chat = $message->getChat();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $this->cancelConversetion();
        $keyboard = Button::GetCurrency();


        $keyboard->setResizeKeyboard(true);


        $data = [
            'chat_id'      => $this->chat->getId(),
            'text'         => Yii::t('telegram', 'Choose the currency you want to exchange'),
            'reply_markup' => $keyboard,
        ];



        return Request::sendMessage($data);        // Send message!
    }

    public function executeMessage()
    {
        $message    = $this->getMessage();
        $this->user = $message->getFrom();
        $this->chat = $message->getChat();


        if ($model = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => PreOrder::STATUS_PAUSE])->one()) {

            $keyboard = new InlineKeyboard([
                ['text' => hex2bin('F09F9AAB') . ' ' . Yii::t('telegram', 'No'), 'callback_data' => 'createorder_close'],
                ['text' => hex2bin("E29C85") . ' ' . Yii::t('telegram', 'Yes'), 'callback_data' => 'createorder_continue_' . $model->id]
            ]);

            $data   = [
                'chat_id'      => $this->chat->getId(),
                'text'         => Yii::t('telegram', 'You have an unfinished order. Do you want to continue?'),
                'reply_markup' => $keyboard,
            ];
        } else {

            $keyboard = Button::GetCurrency();

            $keyboard->setResizeKeyboard(true);


            $data = [
                'chat_id'      => $this->chat->getId(),
                'text'         => Yii::t('telegram', 'Choose the currency you want to exchange'),
                'reply_markup' => $keyboard,
            ];
        }


        return Request::sendMessage($data);        // Send message!
    }
    public function cancelConversetion()
    {
        $model        = new Conversation($this->user->getId(), $this->chat->getId(), $this->getName());
        if ($conversation = ConversationDB::selectConversation($this->user->getId(), $this->chat->getId(), 1)) {
            $id = ArrayHelper::getColumn($conversation, 'id')[0];
            if (!PreOrder::find()->where(['user_id' => $this->user->getId(), 'chat_id' => $this->chat->getId(), 'conversation_id' => $id])->one()) {
                $model->cancel();
            }
        }
    }

}
