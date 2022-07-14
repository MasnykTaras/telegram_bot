<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use common\models\PreOrder;
use common\models\Order;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Commands\UserCommand;
use common\components\telegram\components\tInterface\Button;

class MyordersCommand extends UserCommand
{

    protected $name        = 'myorders';                      // Your command's name
    protected $description = 'View all user orders'; // Your command description
    protected $usage       = '/myorders';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $query_data;
    protected $chat;
    protected $user;
    protected $text;

    public function execute()
    {

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
        $message          = $callback->getMessage();
        $query_data       = strip_tags($callback->getData());
        $this->query_data = explode('_', $query_data);
        $this->chat       = $message->getChat();
        $this->user       = $callback->getFrom();
        $this->text       = strip_tags(trim($message->getText(true)));
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());

        if ($this->query_data[1] == 'all') {
            return $this->pageOrders(1);
        }
        if ($this->query_data[1] == 'view') {
            if (is_numeric($this->query_data[2])) {
                return $this->viewOrder();
            }
        }
        if ($this->query_data[1] == 'page') {
            if (is_numeric($this->query_data[2])) {
                return $this->pageOrders();
            }
        }
        return $this->goToStart();
    }

    protected function sendNewKeyboard()
    {
        $keyboard = Button::StartBtn($this->user->getId());
        $keyboard->setResizeKeyboard(true);
        $data = [
            'chat_id'      => $this->chat->getId(),
            'text'         => 'test',
            'reply_markup' => $keyboard
        ];

        return Request::sendMessage($data);
    }

    protected function goToStart()
    {
        $update['message']['callback'] = Yii::t('telegram', 'Not correct request');
        $update['message']['chat'] = (array) $this->chat;
        $update['message']['from'] = (array) $this->user;
        return (new StartCommand($this->telegram, new Update($update)))->preExecute();
    }

    protected function viewOrder($id = false)
    {
        if ($id || isset($this->query_data[2])) {
            ($id) ? $id    = $id : $id    = $this->query_data[2];
            if ($model = Order::find()->where(['id' => $id, 'user_id' => $this->user->getId()])->one()) {
                $message = $model->orderInfo;
            } else {
                $message = Yii::t('telegram', 'No order by this ID');
            }
        } else {
            $message = Yii::t('telegram', 'Not correct request');
        }

        $data = [
            'chat_id' => $this->chat->getId(),
            'text'    => $message
        ];

        return Request::sendMessage($data);
    }

    protected function pageOrders($page = false)
    {
        $data = ['chat_id' => $this->chat->getId()];

        if ($page || isset($this->query_data[2])) {
            ($page) ? $page     = $page : $page     = $this->query_data[2];
            if ($keyboard = Button::GetOrders($this->user->getId(), $page)) {
                $message              = Yii::t('telegram', 'My Orders');
                $keyboard->setResizeKeyboard(true);
                $data['reply_markup'] = $keyboard;
            } else {
                $message = Yii::t('telegram', 'You don\'t have any orders');
            }
        } else {
            $message = Yii::t('telegram', 'Not correct request');
        }
        $data['text'] = $message;
        return Request::sendMessage($data);
    }
    
    public function executeMessage()
    {
        $message = $this->getMessage();            // Get Message object
        $this->chat = $message->getChat();
        $this->user = $message->getFrom();
        $this->text         = strip_tags(trim($message->getText(true)));
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $params             = explode(' ', $this->text);

        if ($params[0] == 'view') {
            if (is_numeric($params[1])) {
                return $this->viewOrder($params[1]);
            }
        }
        if ($params[0] == 'page') {
            if (is_numeric($params[1])) {
                return $this->pageOrders($params[1]);
            }
        }
        if ($this->text == 'all') {

            return $this->pageOrders(1);
        }
        return $this->goToStart();
    }

}
