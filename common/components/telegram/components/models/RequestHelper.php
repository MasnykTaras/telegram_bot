<?php
 namespace common\components\telegram\components\models;

 use Yii;
use Longman\TelegramBot\Request;
use common\models\TelegramBotUser;
use common\components\telegram\TelegramBot;
use Longman\TelegramBot\Entities\InlineKeyboard;
use modules\statistic\common\models\OrdersCreatingStatistic;

class RequestHelper
{

    public static function sendOrderRequest($model)
    {
        Yii::$app->language    = TelegramBotUser::userLanguage($model->user_id);
        $bot                   = new TelegramBot(env('BOT_API_KEY'), env('BOT_USER_NAME'));
        $keyboard              = new InlineKeyboard([
            ['text' => Yii::t('telegram', 'View order info'), 'callback_data' => 'myorders_view_' . $model->id],
        ]);
        $keyboard->setResizeKeyboard(true);
        $data ['chat_id']      = $model->chat_id;
        $data ['text']         = Yii::t('telegram', 'Your order {id} was change status to {status}', ['id' => $model->order_id, 'status' => $model->statusName]);
        $data ['reply_markup'] = $keyboard;
        return Request::sendMessage($data);
    }

    public static function sendCardRequest($model)
    {
        Yii::$app->language = TelegramBotUser::userLanguage($model->user_id);
        $bot                = new TelegramBot(env('BOT_API_KEY'), env('BOT_USER_NAME'));

        OrdersCreatingStatistic::updateDayCounters(['card_verify_end' => 1]);
        $data ['chat_id'] = $model->user_id;
        $data ['text']    = Yii::t('telegram', 'Your card {card_number} was change status to {status}', ['card_number' => $model->card_number, 'status' => $model->statusName]);

        return Request::sendMessage($data);
    }
    public static function sendEmailRequest($model)
    {
        Yii::$app->language = TelegramBotUser::userLanguage($model->user_id);
        $bot                = new TelegramBot(env('BOT_API_KEY'), env('BOT_USER_NAME'));
        OrdersCreatingStatistic::updateDayCounters(['email_verify_end' => 1]);
        $data ['chat_id'] = $model->user_id;
        $data ['text']    = Yii::t('telegram', 'Your email {email} was change status to {status}', ['email' => $model->email, 'status' => $model->statusName]);

        return Request::sendMessage($data);
    }
    public static function sendServerWork($user_id)
    {
        Yii::$app->language = TelegramBotUser::userLanguage($user_id);
        $bot                = new TelegramBot(env('BOT_API_KEY'), env('BOT_USER_NAME'));

        $data ['chat_id'] = $user_id;
        $data ['text']    = Yii::t('telegram', 'Please accept our apologies. There are technical works on the server. Please try again later.');

        return Request::sendMessage($data);
    }
    public static function sendDiscountRequest($model)
    {
        Yii::$app->language = TelegramBotUser::userLanguage($model->id);
        $bot                = new TelegramBot(env('BOT_API_KEY'), env('BOT_USER_NAME'));

        $data ['chat_id'] = $model->id;
        $data ['text']    = Yii::t('telegram', 'Your discount was change to {discount}%', ['discount' => $model->discount * 100]);

        return Request::sendMessage($data);
    }
    public static function sendSpamRequest($user_id, $text)
    {
        Yii::$app->language = TelegramBotUser::userLanguage($user_id);
        $bot                   = new TelegramBot(env('BOT_API_KEY'), env('BOT_USER_NAME'));
        $keyboard              = Button::StartBtn($user_id);
        $keyboard->setResizeKeyboard(true);
        $data ['reply_markup'] = $keyboard;
        $data ['chat_id'] = $user_id;
        $data ['text']    = $text;

        return Request::sendMessage($data);   
    }
}
