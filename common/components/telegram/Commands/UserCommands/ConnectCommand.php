<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use common\models\PreOrder;
use common\models\ApiManager;
use common\models\WidgetText;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;

class ConnectCommand extends UserCommand
{
    const ERROR_TYPE_1 = 1;
    const ERROR_TYPE_2 = 2;
    const ERROR_TYPE_3 = 3;
    const ERROR_TYPE_4 = -3;

    protected $name        = 'connect';                      // Your command's name
    protected $description = 'A command for set discount'; // Your command description
    protected $usage       = '/connect';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $chat;
    protected $user;
    protected $query_data;

    public function execute()
    {
        // Command triggered with callback
        if ($this->getCallbackQuery()) {

            return $this->executeCallback();
        }

        // Command triggered with regular message
        return $this->executeMessage();      // Send message!
    }

    public function executeCallback()
    {
        $callback           = $this->getCallbackQuery();
        $message            = $callback->getMessage();
        $query_data         = strip_tags($callback->getData());
        $this->query_data   = explode('_', $query_data);
        $this->chat         = $message->getChat();
        $this->user       = $callback->getFrom();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $this->closePreOrder();
        $this->conversation = new Conversation($this->user->getId(), $this->chat->getId(), $this->getName());
        $this->notes        = &$this->conversation->notes;
        if (!isset($this->notes['state'])) {
            $this->notes['state'] = 2;
        }
        $this->conversation->update();
        $data['chat_id'] = $this->chat->getId();
        $data ['text']   = Yii::t('telegram', 'Please, send your secret key');

        return Request::sendMessage($data);
    }

    public function executeMessage()
    {
        $message            = $this->getMessage();
        $this->chat         = $message->getChat();
        $this->user         = $message->getFrom();
        $this->text = strip_tags(trim($message->getText(true)));
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $this->closePreOrder();
        $this->conversation = new Conversation($this->user->getId(), $this->chat->getId(), $this->getName());
        $this->notes        = &$this->conversation->notes;
        
       
        if (isset($this->notes['state'])) {
            $state = $this->notes['state'];
        } else {
            $state = 1;
        }
        if ($state == 1) {

            if (TelegramBotUser::isConnected($this->user->getId())) {
                $data = [
                    'chat_id' => $this->chat->getId(),
                    'text'    => Yii::t('telegram', 'You already connected your telegram account with 365cash'),
                ];
                return Request::sendMessage($data);
            } else {
                $keyboard             = new InlineKeyboard([
                    ['text' => Yii::t('telegram', 'Enter Key'), 'callback_data' => 'connect_button']
                ]);
                $keyboard->setResizeKeyboard(true);
                $data['chat_id']      = $this->chat->getId();
                $data['reply_markup'] = $keyboard;
                $data ['text']        = WidgetText::getText('connect-to-cash', $this->user->getID());
                return Request::sendMessage($data);
            }
        }
        if ($state == 2) {
            return $this->setConnection();
        }
    }

    public function setConnection()
    {
        if (preg_match("/^[a-zA-Z0-9\_\-]{32}$/", $this->text)) {
            if (!TelegramBotUser::find()->where(['hash' => $this->text])->one()) {
                $api    = new ApiManager();
                if ($result = $api->createConnected($this->text, $this->user->id)) {
                    if ($result->result->success) {
                        if ($user = TelegramBotUser::find()->where(['id' => $this->user->id])->one()) {
                            $user->is_connected = TelegramBotUser::CONNECTED_TRUE;
                            $user->hash         = $this->text;
                            $user->discount     = $result->result->details->user->discount;
                            if ($user->save()) {
                                $message = Yii::t('telegram', 'Your account connected successfully');
                                $this->conversation->stop();
                            } else {
                                $message = self::getErrorText(self::ERROR_TYPE_1);
                                $this->conversation->stop();
                            }
                        } else {
                            $message = self::getErrorText(self::ERROR_TYPE_1);
                            $this->conversation->stop();
                        }
                    } else {
                        if (isset($result->result->errorCode)) {
                            $message = self::getErrorText($result->result->errorCode);
                        } else {
                            $message = self::getErrorText(self::ERROR_TYPE_1);
                            $this->conversation->stop();
                        }
                    }
                } else {
                    $message = self::getErrorText($result->result->error->code);
                }
            } else {
                $message = self::getErrorText(self::ERROR_TYPE_3);
            }
        } else {
            $message = Yii::t('telegram', 'Please, send your secret key') . PHP_EOL;
            $message .= Yii::t('telegram', 'Invalid secret key');
        }
        
        $data['chat_id'] = $this->chat->getId();
        $data ['text']   = $message;
        return Request::sendMessage($data);
    }
    public static function getErrorText($code)
    {
        $status = self::errorText();
        return (isset($status[$code])) ? $status[$code] : Yii::t('common', 'Server error');
    }

    public static function errorText()
    {
        return [
            self::ERROR_TYPE_1 => Yii::t('common', 'Server error'),
            self::ERROR_TYPE_2 => Yii::t('common', 'Hash not found'),
            self::ERROR_TYPE_3 => Yii::t('common', 'The hash already active'),
            self::ERROR_TYPE_4 => Yii::t('common', 'Hash already in use'),
        ];
    }
    private function closePreOrder()
    {
        if ($model = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => PreOrder::STATUS_PAUSE])->one()) {
            $model->status = PreOrder::STATUS_CANCELED;
            $model->save();
        }
    }

}
