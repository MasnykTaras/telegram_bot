<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use common\models\PreOrder;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Commands\UserCommand;
use common\components\telegram\components\tInterface\Button;

class LanguageCommand extends UserCommand
{
  
    protected $name        = 'language';                      // Your command's name
    protected $description = 'A command for set language'; // Your command description
    protected $usage       = '/language';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    public $language;
    protected $chat;
    protected $user;

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
        $data['chat_id'] = $this->chat->getId();

        if (count($this->query_data) == 2) {
            if ($this->setLenguage()) {
                $data ['text']        = Yii::t('telegram', 'Your language was change to {lang}', ['lang' => Yii::$app->params['availableLocales'][$this->language]]);
                $keyboard             = Button::StartBtn();
                $keyboard->setResizeKeyboard(true);
                $data['reply_markup'] = $keyboard;
            } else {
                $data ['text'] = Yii::t('telegram', 'Something wrong.');
            }
            return Request::sendMessage($data);
        }
        $keyboard             = Button::GetLanguage();
        $keyboard->setResizeKeyboard(true);
        $data['reply_markup'] = $keyboard;
        $data ['text']        = Yii::t('telegram', 'Choose preferred language from list');
        return Request::sendMessage($data);
    }

    public function executeMessage()
    {
        $message            = $this->getMessage();
        $this->chat         = $message->getChat();
        $this->user         = $message->getFrom();
        $this->text           = strip_tags(trim($message->getText(true)));
     
        Yii::$app->language   = TelegramBotUser::userLanguage($this->user->getId());
        $this->closePreOrder();
        $keyboard             = Button::GetLanguage();
        $keyboard->setResizeKeyboard(true);
        $data['chat_id']      = $this->chat->getId();
        $data['reply_markup'] = $keyboard;
        $data ['text']        = Yii::t('telegram', 'Choose preferred language from list');

        return Request::sendMessage($data);
    }

    private function setLenguage()
    {
        if ($result = $this->languageConformity()) {
            $user               = TelegramBotUser::find()->where(['id' => $this->user->getId()])->one();
            $user->preferred_language = $this->language;
            $user->save(false, ['preferred_language']);
            Yii::$app->language = $this->language;
        }
        return $result;
    }

    private function closePreOrder()
    {
        if ($model = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => PreOrder::STATUS_PAUSE])->one()) {
            $model->status = PreOrder::STATUS_CANCELED;
            $model->save();
        }
    }

    private function languageConformity()
    {
        if (isset(Yii::$app->params['availableLocales'][$this->query_data[1]])) {
            $this->language = $this->query_data[1];
            return true;
        }
        return false;
    }

}
