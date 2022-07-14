<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Yii;
use common\models\Card;
use common\models\Order;
use common\models\PreOrder;
use common\models\Direction;
use yii\helpers\ArrayHelper;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\ConversationDB;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Commands\UserCommands\StartCommand;
use modules\statistic\common\models\OrdersCreatingStatistic;
use common\components\telegram\components\tInterface\Button;
use Longman\TelegramBot\Commands\UserCommands\MyordersCommand;

class CreateorderCommand extends UserCommand
{

    protected $name         = 'createorder';                      // Your command's name
    protected $description  = 'View all currensies'; // Your command description
    protected $usage        = '/createorder';                    // Usage of your command
    protected $version      = '1.0.0';                  // Version of your command
    protected $need_mysql   = true;
    protected $private_only = true;
    protected $conversation;
    protected $user;
    protected $chat;
    protected $text;
    protected $query_data;
    protected $notes;
    protected $preOrder;


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
        $callback           = $this->getCallbackQuery();
        $message            = $callback->getMessage();
        $query_data         = strip_tags($callback->getData());
        $this->query_data   = explode('_', $query_data);
        $this->chat         = $message->getChat();
        $this->user         = $callback->getFrom();
        $this->text         = trim($message->getText(true));
        $this->conversation = new Conversation($this->user->getId(), $this->chat->getId(), $this->getName());
        $this->notes        = &$this->conversation->notes;
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        if (!isset($this->notes['state'])) {
            $this->notes['state'] = 1;
        }

        if ($this->query_data[1] == 'continue') {
            return $this->continueCallback();
        }
        if ($this->query_data[1] == 'changecard') {
            return $this->changeCardCallback();
        }
        if ($this->query_data[1] == 'changeemail') {
            return $this->changeEmailCallback();
        }
        if ($this->query_data[1] == 'close') {
            return $this->closeCallback();
        }
        if ($this->query_data[1] == 'access') {
            return $this->accessCallback();
        }
       
        if ($this->notes['state'] == 1 && $this->query_data[1] == 'reverse') {

            return $this->reverseCallback();
        }
        if ($this->notes['state'] == 1 && $this->query_data[1] == 'normal') {
            return $this->normalCallback();
        }
        return $this->goToStart();
    }

    protected function goToStart()
    {
        $this->conversation->stop();
        $update['message']['text']                  = '/start';
        $update['message']['chat'] = (array) $this->chat;
        $update['message']['from'] = (array) $this->user;
        return (new StartCommand($this->telegram, new Update($update)))->preExecute();
    }

    protected function closeCallback()
    {
        $model = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => [PreOrder::STATUS_PAUSE, PreOrder::STATUS_INPROGRESS]])->one();
        if ($model) {
            $model->status = $model::STATUS_CANCELED;
            if ($model->save()) {
                if (isset($this->query_data[2]) && $model->direction_id == $this->query_data[2]) {
                    OrdersCreatingStatistic::updateDayCounters(['canceled_preorder' => 1]);
                } else {
                    OrdersCreatingStatistic::updateDayCounters(['canceled_continued' => 1]);
                }
                $this->conversation->cancel();
                $update['message']['text'] = '/neworder';
                $update['message']['chat'] = (array) $this->chat;
                $update['message']['from'] = (array) $this->user;
                return (new NeworderCommand($this->telegram, new Update($update)))->preExecute();
            }
        }
    }
    protected function changeCardCallback()
    {
        $model = PreOrder::find()->where(['id' => $this->query_data[2]])->one();
        $model->updateAttributes(['sell_wallet' => null]);

        $data['parse_mode'] = 'markdown';
        $data['chat_id'] = $this->chat->getId();
        $data ['text']   = hex2bin('F09F919B') . ' ' . Yii::t('telegram', 'Write your sell address in the format') . " *\"" . $model->direction->sellCurrency->placeholder . "\"*" . PHP_EOL;
        return Request::sendMessage($data);
    }
    protected function changeEmailCallback()
    {
        $model = PreOrder::find()->where(['id' => $this->query_data[2]])->one();
        $model->updateAttributes(['main_email' => null]);

        $data['parse_mode'] = 'markdown';
        $data['chat_id'] = $this->chat->getId();
        $data ['text']   = hex2bin('F09F919B') . ' ' . Yii::t('telegram', 'Write your Email in the format') . " \"example@mail.com\"" . PHP_EOL;
        return Request::sendMessage($data);
    }

    protected function accessCallback()
    {
        $preOrder = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => PreOrder::STATUS_INPROGRESS])->one();
        
        if ($preOrder) {
            $order  = new Order();
            $answer = $order->addNewOrder($this->user->getId(), $preOrder);
            if ($answer['result']) {
                $this->conversation->cancel();
                $preOrder->status = PreOrder::STATUS_DONE;
                if ($preOrder->save()) {
                    OrdersCreatingStatistic::updateDayCounters(['completed' => 1]);
                    $update['message']['text'] = '/myorders view' . ' ' . $order->id;
                    $update['message']['chat'] = (array) $this->chat;
                    $update['message']['from'] = (array) $this->user;

                    return (new MyordersCommand($this->telegram, new Update($update)))->preExecute();
                } else {
                    $answer['message'] = Yii::t('telegram', 'The order didn\'t create. Please try later. Take our apologies.');
                }
            }
            if (isset($answer['fatalError']) && $answer['fatalError']) {
                $this->conversation->cancel();
                $preOrder->status  = PreOrder::STATUS_CANCELED;
                $preOrder->save();
                $answer['message'] .= Yii::t('telegram', 'The current pre-order will be removed.') . PHP_EOL;
            }
        } else {
            $answer['message'] = Yii::t('telegram', 'The order didn\'t create. Please try later. Take our apologies.');
        }
        $data ['chat_id'] = $this->chat->getId();
        $data ['text']    = $answer['message'];
        return Request::sendMessage($data);
    }

    protected function reverseCallback()
    {
        $direction = Direction::find()->where(['id' => $this->query_data[2]])->one();
        if ($direction) {
            $this->notes['reverse']               = true;
            $this->notes['order']['direction_id'] = $direction->id;
            $this->conversation->update();
            $min                                  = (float) $direction->minBuyAmount;
            $max                                  = (float) $direction->maxBuyAmount;
            $name                   = $direction->buyCurrency->name;

            $keyboard = new InlineKeyboard([
                ['text' => Yii::t('telegram', 'Indicate the amount in {currency_name}', ['currency_name' => $direction->sellCurrency->name]), 'callback_data' => 'createorder_normal_' . $direction->id],
                ['text' => Yii::t('telegram', 'Choose another currency'), 'callback_data' => 'neworder_cancel']
            ]);
            $keyboard->setResizeKeyboard(true);

            $data['chat_id']      = $this->chat->getId();
            $data['reply_markup'] = $keyboard;
            $data ['text']        = Yii::t('telegram', 'Write me the amount you want to exchange from {min_amount} to {max_amount} {currency_name}', ['min_amount' => $min, 'max_amount' => $max, 'currency_name' => $name]);
            return Request::sendMessage($data);
        }
        return $this->goToStart();
    }

    protected function normalCallback()
    {
        $direction = Direction::find()->where(['id' => $this->query_data[2]])->one();
        if ($direction) {
            $this->notes['reverse']               = false;
            $this->notes['order']['direction_id'] = $direction->id;
            $this->conversation->update();
            $min                                  = (float) $direction->minSellAmount;
            $max                                  = (float) $direction->maxSellAmount;
            $name                                 = $direction->sellCurrency->name;

            $keyboard             = new InlineKeyboard([
                ['text' => Yii::t('telegram', 'Indicate the amount in {currency_name}', ['currency_name' => $direction->buyCurrency->name]), 'callback_data' => 'createorder_reverse_' . $direction->id],
                ['text' => Yii::t('telegram', 'Choose another currency'), 'callback_data' => 'neworder_cansel']
            ]);
            $keyboard->setResizeKeyboard(true);
            $data['chat_id']      = $this->chat->getId();
            $data['reply_markup'] = $keyboard;
            $data ['text']        = Yii::t('telegram', 'Write me the amount you want to exchange from {min_amount} to {max_amount} {currency_name}', ['min_amount' => $min, 'max_amount' => $max, 'currency_name' => $name]);
            return Request::sendMessage($data);
        }

        return $this->goToStart();
    }
    protected function continueCallback()
    {
        $this->preOrder = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => [PreOrder::STATUS_PAUSE, PreOrder::STATUS_INPROGRESS]])->one();
        if ($this->preOrder) {
            OrdersCreatingStatistic::updateDayCounters(['canceled_continued' => 1]);
            $this->preOrder->updateStatusToProgress();
            return $this->formFillingMessage(true);
        }
        return $this->goToStart();
    }

    public function executeMessage()
    {
        $message            = $this->getMessage();
        $this->chat         = $message->getChat();
        $this->user         = $message->getFrom();
        $this->text         = strip_tags(trim($message->getText(true)));
        Yii::$app->language = TelegramBotUser::userLanguage($this->user->getId());
        $this->conversation = new Conversation($this->user->getId(), $this->chat->getId(), $this->getName());
        $this->notes        = &$this->conversation->notes;
        if ($this->text == 'continue') {
            return $this->continueCallback();
        }
        if (!isset($this->notes['order'])) {
            return $this->goToStart();
        }
        if (isset($this->notes['state'])) {
            $state = $this->notes['state'];
        } else {
            $state = 1;
        }
        $this->preOrder = PreOrder::find()->where(['user_id' => $this->user->getId(), 'status' => PreOrder::STATUS_INPROGRESS])->one();
        if (!$this->preOrder) {
            $this->preOrder               = new PreOrder();
            $this->preOrder->user_id      = $this->user->getId();
            $this->preOrder->chat_id      = $this->chat->getId();
            $this->preOrder->direction_id = $this->notes['order']['direction_id'];
            $this->preOrder->conversation_id = $this->getConversationID();
            OrdersCreatingStatistic::updateDayCounters(['start' => 1]);
        }
        if ($state == 1) {
            return $this->amountMessage();
        }
        if ($state == 2) {
            return $this->formFillingMessage();
        }
       
        return $this->goToStart();
    }

    protected function amountMessage()
    {
        $data['chat_id'] = $this->chat->getId();
        $direction       = Direction::find()->where(['id' => $this->notes['order']['direction_id']])->one();
        if ($direction) {
            $result = $direction->convertToNumber($this->text);

            if ($result) {
                if ($this->notes['reverse']) {
                    if ($direction->minBuyAmount <= $result && $direction->maxBuyAmount >= $result) {
                        $this->notes['state']        = 2;
                        $this->preOrder->rate        = $direction->getCurrentRate($this->user->getId());
                        $this->preOrder->sell_amount = $direction->recalculateAmount(false, $result, $direction->getCurrentRate($this->user->getId()), true);
                        if ($this->preOrder->save()) {
                            $answer = $this->preOrder->getNextField()->answer;
                        }
                    } else {
                        $answer = Yii::t('telegram', 'Be a little more careful! Now we can’t exchange so much.') . PHP_EOL;
                    }
                } else {
                    if ($direction->minSellAmount <= $result && $direction->maxSellAmount >= $result) {
                        $this->notes['state']        = 2;
                        $this->preOrder->rate        = $direction->getCurrentRate($this->user->getId());
                        $this->preOrder->sell_amount = $result;
                        if ($this->preOrder->save()) {
                            $answer = $this->preOrder->getNextField()->answer;
                        }
                    } else {
                        $answer = Yii::t('telegram', 'Be a little more careful! Now we can’t exchange so much.') . PHP_EOL;
                    }
                }
            } else {
                $answer = Yii::t('telegram', 'Be a little more careful!') . PHP_EOL;
            }

            $this->conversation->update();
            $data['text'] = $answer;
            $data['parse_mode'] = 'markdown';
            return Request::sendMessage($data);
        }
        
    }

    protected function formFillingMessage($continue = false)
    {

        $not_end         = true;
        $answer          = '';
        $data['chat_id'] = $this->chat->getId();
        if ($continue) {
            $result = $this->preOrder->getNextField();
            $not_end = $result->result;
            if ($result->result) {
                $answer .= $this->preOrder->getFillingValue();
                $answer .= $result->answer;
            }
        } else {
            $result = $this->preOrder->formFilling($this->text);
            $answer .= $result->answer;
            if ($result->next) {
                $next   = $this->preOrder->getNextField();
                $answer .= $next->answer;
            } else if ($result->field) {
                $next   = $this->preOrder->getNextField($result->field);
                $answer .= $next->answer;
            }
            $not_end = ($result->next && !$next->result) ? false : true;
        }
       
        
        if (!$not_end) {
            $answer               = $this->preOrder->createPreOrderInfo();
            $keyboard             = Button::GetPreOrderAndButtons($this->preOrder);
            $keyboard->setResizeKeyboard(true);
            $data['text']         = $answer;
            $data['reply_markup'] = $keyboard;
            return Request::sendMessage($data);
        }
       
        $data['text'] = $answer;
        $data['parse_mode'] = 'markdown';
        return Request::sendMessage($data);
    }
    public function getConversationID()
    {
        if ($conversation = ConversationDB::selectConversation($this->user->getId(), $this->chat->getId(), 1)) {
            return ArrayHelper::getColumn($conversation, 'id')[0];
        }
    }

}
