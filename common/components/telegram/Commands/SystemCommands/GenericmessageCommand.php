<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Yii;
use common\models\PreOrder;
use yii\helpers\ArrayHelper;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\ConversationDB;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\AboutCommand;
use Longman\TelegramBot\Commands\UserCommands\StartCommand;
use common\components\telegram\components\tInterface\Button;
use Longman\TelegramBot\Commands\UserCommands\SupportCommand;
use Longman\TelegramBot\Commands\UserCommands\NeworderCommand;
use Longman\TelegramBot\Commands\UserCommands\LanguageCommand;
use Longman\TelegramBot\Commands\UserCommands\MyordersCommand;
use Longman\TelegramBot\Commands\UserCommands\CreateorderCommand;


class GenericmessageCommand extends SystemCommand
{

    protected $name        = 'Genericmessage';
    protected $description = 'Handle generic message';
    protected $version     = '1.0.0';
    protected $user;
    protected $chat;
    protected $conversation;

    public function execute()
    {
        $message            = $this->getMessage();
        $this->user         = $message->getFrom();
        $this->chat         = $message->getChat();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $text               = strip_tags($message->getText(true));
        $this->conversation = new Conversation($this->user->getId(), $this->chat->getId());
        $this->sendNewKeyboard();

        if ($text) {

            if ($text == Yii::t('telegram', 'New order')) {
                $update['message']['text'] = '/neworder';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                $this->cancelConversetion();
                PreOrder::pausePreOrder($this->user->getId(), $this->chat->getId());
                return (new NeworderCommand($this->telegram, new Update($update)))->preExecute();
            } elseif ($text == Yii::t('telegram', 'My orders')) {
                $update['message']['text'] = '/myorders all';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                $this->cancelConversetion();
                PreOrder::pausePreOrder($this->user->getId(), $this->chat->getId());
                return (new MyordersCommand($this->telegram, new Update($update)))->preExecute();
            } elseif ($text == Yii::t('telegram', 'About Bot')) {
                $update['message']['text'] = '/about';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                $this->cancelConversetion();
                PreOrder::pausePreOrder($this->user->getId(), $this->chat->getId());
                return (new AboutCommand($this->telegram, new Update($update)))->preExecute();
            } elseif ($text == Yii::t('telegram', 'Continue')) {
                $update['message']['text'] = '/createorder continue';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                $this->cancelConversetion();
                PreOrder::pausePreOrder($this->user->getId(), $this->chat->getId());
                return (new CreateorderCommand($this->telegram, new Update($update)))->preExecute();
            } elseif ($text == Yii::t('telegram', 'Support')) {
                $update['message']['text'] = '/support';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                $this->cancelConversetion();
                PreOrder::pausePreOrder($this->user->getId(), $this->chat->getId());
                return (new SupportCommand($this->telegram, new Update($update)))->preExecute();
            } elseif ($text == Yii::t('telegram', 'Language/Язык/Мова')) {
                $update['message']['text'] = '/language';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                $this->cancelConversetion();
                PreOrder::pausePreOrder($this->user->getId(), $this->chat->getId());
                return (new LanguageCommand($this->telegram, new Update($update)))->preExecute();
            } else {
            if (!PreOrder::find()->where(['user_id' => $this->user->getId(), 'chat_id' => $this->chat->getId(), 'status' => PreOrder::STATUS_PAUSE])->one()) {
                    //Fetch conversation command if it exists and execute it
                    if ($this->conversation->exists() && ($command = $this->conversation->getCommand())) {
                        return $this->telegram->executeCommand($command);
                    }
                }
            }
        }
        
        return Request::emptyResponse();
    }
     protected function sendNewKeyboard()
    {
        $keyboard = Button::StartBtn($this->user->getId());
        $keyboard->setResizeKeyboard(true);
        $data     = [
            'chat_id'      => $this->chat->getId(),
            'text'         => '...',
            'reply_markup' => $keyboard
        ];

        return Request::sendMessage($data);
    }
    protected function cancelConversetion()
    {

        if ($conversation = ConversationDB::selectConversation($this->user->getId(), $this->chat->getId(), 1)) {
            $id = ArrayHelper::getColumn($conversation, 'id')[0];
            if (!PreOrder::find()->where(['user_id' => $this->user->getId(), 'chat_id' => $this->chat->getId(), 'conversation_id' => $id])->one()) {
                $this->conversation->cancel();
            }
        }
    }

}
