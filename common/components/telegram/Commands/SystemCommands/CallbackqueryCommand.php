<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Yii;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Commands\SystemCommand;
use common\components\telegram\components\tInterface\Button;

/**
 * Callback query command
 */
class CallbackqueryCommand extends SystemCommand
{

    /**
     * @var callable[]
     */
    protected static $callbacks = [];

    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.0.0';
    protected $user;
    protected $chat;
    protected $query_data;

    /**
     * Command execute method
     *
     * @return mixed
     */
    public function execute()
    {

        // Explode query_data into chunks
        $callback   = $this->getCallbackQuery();
        $message          = $callback->getMessage();
        $query_data       = strip_tags($callback->getData());
        $this->query_data = explode('_', $query_data);
        $this->user = $message->getFrom();
        $this->chat         = $message->getChat();
        Yii::$app->language = TelegramBotUser::userLanguage($this->chat->raw_data['id']);
        $this->sendNewKeyboard();

        // If there are two pieces, consider first one a command name and execute it
        if (\count($this->query_data) >= 2) {
            return $this->getTelegram()->executeCommand($this->query_data[0]);
        }

        return Request::answerCallbackQuery(['callback_query_id' => $this->getUpdate()->getCallbackQuery()->getId()]);
    }

    /**
     * Add a new callback handler for callback queries.
     *
     * @param $callback
     */
    public static function addCallbackHandler($callback)
    {
        self::$callbacks[] = $callback;
    }
    protected function sendNewKeyboard()
    {
        if ($this->query_data[0] == 'createorder' && $this->query_data[1] == 'close') {
            $id = false;
        } else {
            $id = $this->user->getId();
        }
        $keyboard = Button::StartBtn();
        $keyboard->setResizeKeyboard(true);
        $data     = [
            'chat_id'      => $this->chat->getId(),
            'text'         => '...',
            'reply_markup' => $keyboard
        ];

        return Request::sendMessage($data);
    }

}
