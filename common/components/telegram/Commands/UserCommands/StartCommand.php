<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use common\components\telegram\components\tInterface\Button;

class StartCommand extends UserCommand
{

    protected $name        = 'start';                      // Your command's name
    protected $description = 'A command for test'; // Your command description
    protected $usage       = '/start';                    // Usage of your command
    protected $version     = '1.0.0';                  // Version of your command
    protected $chat;
    protected $user;

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object
        $this->chat = $message->getChat();
        $this->user         = $message->getFrom();
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $keyboard = Button::StartBtn();

        $keyboard->setResizeKeyboard(true);
        (isset($this->user->username)) ? $name = $this->user->username : (isset($this->user->first_name)) ? $name = $this->user->first_name : $name = '';
        (isset($message->callback)) ? $text = $message->callback : $text = Yii::t('telegram', 'Hello! Let\'s start.') . ' ' . $name;

        $text .= PHP_EOL . Yii::t('telegram', 'To choose language use command {command}', ['command' => '/language']);
        $data     = [
            'chat_id'      => $this->chat->getId(),
            'text'         => $text,
            'reply_markup' => $keyboard,
        ];

        return Request::sendMessage($data);        // Send message!
    }

}
