<?php

namespace common\components\telegram\components\tInterface;

use Yii;
use common\models\Card;
use common\models\Email;
use common\models\PreOrder;
use common\models\Order;
use common\models\Currency;
use common\models\Direction;
use yii\helpers\ArrayHelper;
use common\models\TelegramBotUser;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

class Button
{
    const LIMIT_ORDERS = 5;

    public static function StartBtn($user_id = false)
    {
        $button = [
            ['text' => Yii::t('telegram', 'New order')],
            ['text' => Yii::t('telegram', 'My orders')],
            ['text' => Yii::t('telegram', 'About Bot')],
            ['text' => Yii::t('telegram', 'Support')],
        ];
        
        if ($user_id) {
            if ($model = PreOrder::find()->where(['user_id' => $user_id, 'status' => [PreOrder::STATUS_PAUSE, PreOrder::STATUS_INPROGRESS]])->one()) {
                $button[] = ['text' => Yii::t('telegram', 'Continue')];
            }
        }

        $max_per_row = 2; // or however many you want!
        $rows        = array_chunk($button, $max_per_row);
        $rows[]      = ['text' => Yii::t('telegram', 'Language/Язык/Мова')];
        
        return new Keyboard(...$rows);
    }

    public static function GetCurrency()
    {

        if ($models = Currency::find()->where(['status' => Currency::STATUS_ACTIVE])->all()) {

            $items = array_map(function ($model) {
                
                    return [
                        'text'          => $model->name,
                        'callback_data' => 'currency_get_' . $model->code,
                    ];
                
            }, $models);

            $max_per_row  = 2;
            $rows         = array_chunk($items, $max_per_row);
            $reply_markup = new InlineKeyboard(...$rows);

            return $reply_markup;
        }

        return false;
    }

    public static function GetDirection($code, $userID)
    {
        if ($sellCurrency = Currency::find()->where(['code' => $code, 'status' => Currency::STATUS_ACTIVE])->one()) {
            $buyCurrencies = Currency::find()->where(['status' => Currency::STATUS_ACTIVE])->all();
            $IDs           = ArrayHelper::getColumn($buyCurrencies, 'id');
            if ($models        = Direction::find()->where(['sell_currency_id' => $sellCurrency->id, 'buy_currency_id' => $IDs, 'status' => Direction::STATUS_ACTIVE])->all()) {
                $items = array_map(function ($model) use ($userID) {
                    if (!is_null($model->direction_fields)) {
                        
                        if ($model->maxSellAmount > $model->minSellAmount && $model->maxBuyAmount > $model->minBuyAmount) {
                            return [
                                'text'          => $model->shortInfo($userID),
                                'callback_data' => 'createorder_normal_' . $model->id,
                            ];
                        }
                    }
                }, $models);
                $items = array_filter($items);
                if (empty($items)) {
                    return false;
                }
                $rows         = array_chunk($items, 1);
                $reply_markup = new InlineKeyboard(...$rows);

                return $reply_markup;
            }
        }
        return false;
    }
    public static function GetOrders($user, $page)
    {
        $orderCount = Order::find()->where(['user_id' => $user])->count();
       
        if ($orderCount) {
            $pages = round($orderCount / self::LIMIT_ORDERS);

            if ($page <= $pages) {
                $offset = self::LIMIT_ORDERS * ($page - 1);
                $models = Order::find()->where(['user_id' => $user])->orderBy(['id' => SORT_DESC])->limit(self::LIMIT_ORDERS)->offset($offset)->all();

                $items = array_map(function ($model) {
                    return [
                        'text'          => $model->shortOrderInfo,
                        'callback_data' => 'myorders_view_' . $model->id,
                    ];
                }, $models);

                if ($orderCount > self::LIMIT_ORDERS) {
                    $prev    = $page - 1;
                    $next    = $page + 1;
                    ($page > 1) ? $items[] = ['text' => hex2bin('E2AC85'), 'callback_data' => 'myorders_page_' . $prev] : '';
                    $items[] = ['text' => $page . '/' . $pages, 'callback_data' => 'myorders_page_' . $page];
                    ($next > $pages) ? '' : $items[] = ['text' => hex2bin('E29EA1'), 'callback_data' => 'myorders_page_' . $next];
                }
                $max_per_row = 1; // or however many you want!
                $rows        = array_chunk($items, $max_per_row);

                $reply_markup = new InlineKeyboard(...$rows);

                return $reply_markup;
            }
        }
        return false;
    }

    public static function GetPreOrderAndButtons($preOrder)
    {
        $valid   = true;
        $items[] = ['text' => hex2bin("F09F9AAB") . ' ' . Yii::t('telegram', 'Cancel'), 'callback_data' => 'createorder_close_' . $preOrder->direction_id];

        if ($preOrder->direction->sellCurrency->card_validation && !Card::isCardConfirm($preOrder->sell_wallet)) {
            $valid   = false;
            $items[] = ['text' => hex2bin("F09F9484") . ' ' . Yii::t('telegram', 'Change Card'), 'callback_data' => 'createorder_changecard_' . $preOrder->id];
        }
        if ($preOrder->direction->sellCurrency->email_validation && !Email::isEmailConfirm($preOrder->main_email)) {
            $valid   = false;
            $items[] = ['text' => hex2bin("F09F9484") . ' ' . Yii::t('telegram', 'Change Email'), 'callback_data' => 'createorder_changeemail_' . $preOrder->id];
        }

        if ($valid) {
            $items[] = ['text' => hex2bin("E29C85") . ' ' . Yii::t('telegram', 'Accepted'), 'callback_data' => 'createorder_access_' . $preOrder->direction_id];
        }
        $max_per_row = 1; // or however many you want!
        $rows        = array_chunk($items, $max_per_row);

        $reply_markup = new InlineKeyboard(...$rows);
        return $reply_markup;
    }
    public static function GetLanguage()
    {
        $langs   = Yii::$app->params['availableLocales'];
        
        foreach ($langs as $key => $value) {
            $items[] = ['text' => $value, 'callback_data' => 'language_' . $key];
        }
        $max_per_row  = 1; // or however many you want!
        $rows         = array_chunk($items, $max_per_row);
        $reply_markup = new InlineKeyboard(...$rows);
        return $reply_markup;
    }

}
