<?php

namespace common\models;

use Yii;
use common\models\Card;
use common\models\Email;
use common\models\Order;
use common\models\Currency;
use common\models\Direction;
use common\components\telegram\components\models\RequestHelper;

class ApiManager
{

//    private static $url    = 'http://api.365test.local/exchange/v1/json';
//    private static $bearer = '5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz';
    private static $url;
    private static $bearer;
    private static $userID;

    public function __construct($userID = null)
    {
        self::$userID = $userID;
        self::$url    = env('API_URL');
        self::$bearer = env('API_BEARER');
    }

    public function sendRequest($data_string, $method = "GET")
    {
        $curl   = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL            => self::$url,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $data_string,
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer ' . self::$bearer,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        ));
        $result = curl_exec($curl);
        
        return self::isError($result, $data_string);
    }

    public function checkCurrencies($result)
    {
        if ($result && isset($result->result)) {
            $currencies = $result->result->currencies;
            if ($currencies) {
                $this->updateCurrencyStatus();
                foreach ($currencies as $currency) {
                    if (!$this->currency($currency)) {
                        $this->createNewCurrency($currency);
                    }
                }
                return true;
            }
       }
        return false;
    }

    public function checkDirections($result)
    {
        if ($result) {
            $directions = $result->result->rates;
            if ($directions) {
                $this->updateDirectionStatus();
                foreach ($directions as $direction) {
                    sleep(1);
                    if (!$this->direction($direction)) {

                        $this->createNewDirection($direction);
                    }
                }
                return true;
            }
        }
        return false;
    }
    public function checkDirectionsRate($result)
    {
        
        if (isset($result->result)) {
            $directions = $result->result->rates;
            foreach ($directions as $direction) {
                
                if ($curreniesId = $this->getCurrencyByDirection($direction->code)) {

                    if ($model = Direction::find()->where(['sell_currency_id' => $curreniesId['sell']->id, 'buy_currency_id' => $curreniesId['buy']->id])->one()) {

                        $model->status   = Direction::STATUS_ACTIVE;
                        $model->rate     = $direction->rate;
                        $model->min_sell = $direction->ranges->sell->min;
                        $model->min_buy  = $direction->ranges->buy->min;
                        $model->max_sell = $direction->ranges->sell->max;
                        $model->max_buy  = $direction->ranges->buy->max;
                        if ($model->save()) {
                            $activeDirection[] = $model->id;
                        }
                        if (isset($model->errors)) {
                            $message = json_encode($model->errors);
//                            Yii::warning($message, 'direction_rate');
                        }
                    }
                }
            }
            Direction::updateAll(['status' => Direction::STATUS_DISABLED], ['NOT IN', 'id', $activeDirection]);
        }
    }

    protected function updateCurrencyStatus()
    {
        $model = new Currency();
        if ($model->find()->all())
            $model->updateAll(['status' => Currency::STATUS_DISABLED]);
    }

    protected function updateDirectionStatus()
    {
        $model = new Direction();
        if ($model->find()->all())
            $model->updateAll(['status' => Direction::STATUS_DISABLED]);
    }

    protected function currency($currency)
    {
        if ($model = Currency::find()->where(['code' => $currency->code, 'name' => $currency->name])->one()) {
            $model->updateAttributes([
                'reserve'   => $currency->reserve,
                'status'    => Currency::STATUS_ACTIVE,
                'precision' => $currency->precision
            ]);
            return true;
        }
        return false;
    }

    protected function direction($direction)
    {

        if ($curreniesId = $this->getCurrencyByDirection($direction->code)) {

            if ($model = Direction::find()->where(['sell_currency_id' => $curreniesId['sell']->id, 'buy_currency_id' => $curreniesId['buy']->id])->one()) {

                $model->updateAttributes([
                    'rate'          => $direction->rate,
                    'main_currency' => $model->getMainCurrentID($direction->main),
                    'min_sell'      => $direction->ranges->sell->min,
                    'min_buy'       => $direction->ranges->buy->min,
                    'max_sell'      => $direction->ranges->sell->max,
                    'max_buy'       => $direction->ranges->buy->max,
                    'status'        => Direction::STATUS_ACTIVE
                ]);

                if ($fields = $this->createDirectionInfo($model->directionName)) {

                    if (!isset($fields->error)) {
                        $model->updateAttributes([
                            'direction_fields' => json_encode($fields->result->fields),
                        ]);
                    }
                }

                return true;
            }
        }
        return false;
    }

    protected static function createNewCurrency($currency)
    {

        $model = new Currency();
        $model->preLoad($currency);
        
        if ($model->save()) {
            return true;
        }
        return false;
    }

    protected static function createNewDirection($direction)
    {

        if ($curreniesId = self::getCurrencyByDirection($direction->code)) {

            $model = new Direction();

            $model->sell_currency_id = $curreniesId['sell']->id;
            $model->buy_currency_id  = $curreniesId['buy']->id;
            $model->min_sell         = $direction->ranges->sell->min;
            $model->min_buy          = $direction->ranges->buy->min;
            $model->max_sell         = $direction->ranges->sell->max;
            $model->max_buy          = $direction->ranges->buy->max;

           
            if ($fields = self::createDirectionInfo($model->directionName)) {

                if (!isset($fields->error)) {
                    $model->direction_fields = json_encode($fields->result->fields);
                } else {
                    $model->status = Currency::STATUS_DISABLED;
                }
            }
            if ($id = $model->getMainCurrentID($direction->main)) {
                $model->main_currency = $id;
            } else {
                $model->main_currency = $curreniesId['sell']->id;
            }
            $model->rate = $direction->rate;
            if ($model->save()) {
                return true;
            }
        }

        return false;
    }

    protected static function getCurrencyByDirection($code)
    {

        $curreniesId = [];
        $values      = explode('_', $code);

        foreach ($values as $key => $value) {
            if ($model = Currency::find()->where(['code' => $value])->one()) {

                if ($key == 0) {
                    $curreniesId['sell'] = $model;
                } else {
                    $curreniesId['buy'] = $model;
                }
            }
        }
        if (count($curreniesId) == 2) {

            return $curreniesId;
        }

        return false;
    }

    public static function createDirectionInfo($code)
    {
        return self::sendRequest(self::createDirectionInfoString($code));
    }

//curl -X POST "http://api.365test.local/exchange/v1/json" -H "Authorization: Bearer 5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz" -d '{"jsonrpc":"2.0", "method":"direction.info", "params":{"code":"BTC_QWRUB"},"id":1}'
//curl -X GET "http://api.365test.local/exchange/v1/json" -H "Authorization: Bearer 5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz" -d '{"jsonrpc":"2.0", "method":"direction.info", "params":{"code":"BTC_QWRUB"},"id":1}'

    private static function createDirectionInfoString($code)
    {
        return json_encode(['jsonrpc' => '2.0', 'method' => 'direction.info', "params" => ["code" => $code], 'id' => 1]);
    }
//curl -X POST "http://api.365test.local/exchange/v1/json" -H "Authorization: Bearer 5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz" -d '{"jsonrpc":"2.0","method":"order.create","params":{"code":"QWRUB_BTC","fields":{"main_email":"asd@sad.ds","sell_amount":"43800","sell_wallet":"+380635681350","buy_wallet":"1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2"},"hash":"iplGf-U7r6YjyHO0QjrU_CNsMQa_x_R8"},"id":1}'

    public function createPreorder($fields, $code, $hash, $language, $userID, $userIP)
    {
        return $this->sendRequest($this->createOrderDataString($fields, $code, $hash, $language, $userID, $userIP), 'POST');
    }

//curl -X POST "https://api.365cash.co/exchange/v1/json" -H "Authorization: Bearer nDrFKeA_ktY7VJ6SUoBwqbQ8xTNFncGI-bxknw62" -d '{"jsonrpc":"2.0", "method":"order.create", "params":{"code":"EXMUSD_CARDUAH", "fields":{"main_email":"test@test.tt","sell_amount":"2000055.56","sell_source":"u567567567","buy_wallet":"1234 1234 1234 1234", "buy_card_first_name":"nnn","buy_card_last_name":"fff","buy_phone":"+380837684350"}, "hash":"23asdkfhl3"},"id":1}'
    private function createOrderDataString($fields, $code, $hash, $language, $userID, $userIP)
    {
        return json_encode(['jsonrpc' => '2.0', 'method' => 'order.create', "params" => ["code" => $code, "fields" => $fields, "hash" => $hash, 'options' => ['language' => $language, 'telegramUserId' => $userID, 'userIp' => $userIP]], "id" => 1]);
    }

    public function checkOrdersInfo($order_hash = false)
    {
        if ($order_hash) {
            $models = Order::find()->where(['order_hash' => $order_hash])->all();
        } else {
            $models = Order::find()->where(['>', 'created_at', date('U') + (30 * 3600)])->andWhere(['stayus' => Order::ORDER_STATUS_PENDING])->all();
        }
        $result = '';
        foreach ($models as $model) {
            $result = json_decode($this->createOrderInfo($model->order_hash));
            if (isset($result->result) && isset($result->result->info)) {
                if (isset($result->result->info->status)) {
                    $model->status = $model->getStatusValue($result->result->status);
                    if ($model->save()) {
                        $result .= $model->hash . ' status ' . $result->result->status . PHP_EOL;
                    } else {
                        $result .= $model->hash . ' status error' . PHP_EOL;
                    }
                }
            }
        }
        return $result;
    }
    public function checkOrderInfo($order_hash = null)
    {
        if ($order_hash) {
            $result = $this->sendRequest(json_encode(['jsonrpc' => '2.0', 'method' => 'order.info', "params" => ["orderHash" => $order_hash], "id" => 1]));
            if (isset($result->result) && isset($result->result->info)) {
                $model         = Order::find()->where(['order_hash' => $order_hash])->one();
                $model->status = $model->getStatusValue($result->result->info->status);
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function createOrderInfo($order_hash)
    {
        return $this->sendRequest($this->createOrderInfoDataString($order_hash));
    }

    private function createOrderInfoDataString($order_hash)
    {
        if ($model = Order::find()->where(['order_hash' => $order_hash])->one()) {

            return json_encode(['jsonrpc' => '2.0', 'method' => 'order.info', "params" => ["orderHash" => $model->order_hash], "id" => 1]);
        }
    }

    public function createOrderCancel($order_hash)
    {
        return $this->sendRequest($this->createOrderCancelDataString($order_hash));
    }

    private function createOrderCancelDataString($order_hash)
    {
       return json_encode(['jsonrpc' => '2.0', 'method' => 'order.cancel', "params" => ["orderHash" => $order_hash], "id" => 1]);
    }

    public function createOrderPaid($order_hash)
    {
        return $this->sendRequest($this->createOrderPaidDataString($order_hash));
    }


    private function createOrderPaidDataString($order_hash)
    {
        if ($model = Order::find()->where(['order_hash' => $order_hash])->one()) {
            return json_encode(['jsonrpc' => '2.0', 'method' => 'order.paid', "params" => ["orderHash" => $model->order_hash], "id" => 1]);
        }
    }

//    curl -X POST "http://api.365redo.local/exchange/v1/json" -H "Authorization: Bearer 5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz" -d '{"jsonrpc":"2.0", "method":"card.verify", "params":{"code":"SBERRUB","number":"1234123412341234"},"id":1}'
    public function createEmailValidate($email)
    {
        return $this->sendRequest($this->createEmailValidateDataString($email), 'POST');
    }

    private function createEmailValidateDataString($email)
    {
        return json_encode(['jsonrpc' => '2.0', 'method' => 'email.verify', "params" => ["email" => $email], "id" => 1]);
    }

//    curl -X POST "http://api.365redo.local/exchange/v1/json" -H "Authorization: Bearer 5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz" -d '{"jsonrpc":"2.0", "method":"card.verify", "params":{"code":"SBERRUB","number":"1234123412341234"},"id":1}'
    public function createCardValidate($card_number, $code)
    {
        return $this->sendRequest($this->createCardValidateDataString($card_number, $code), 'POST');
    }

    private function createCardValidateDataString($card_number, $code)
    {
        return json_encode(['jsonrpc' => '2.0', 'method' => 'card.verify', "params" => ["code" => $code, "number" => $card_number], "id" => 1]);
    }
//    curl -X POST "http://api.365redo.local/exchange/v1/json" -H "Authorization: Bearer 5jyKtMbh6CYAeOD_WLQPXo6OWQVB8U-yphvK76Kz" -d '{"jsonrpc":"2.0", "method":"card.verify", "params":{"code":"SBERRUB","number":"1234123412341234"},"id":1}'
    public function createConnected($hash, $telegram_user_id)
    {
        return $this->sendRequest($this->createConnectedDataString($hash, $telegram_user_id), 'POST');
    }

    private function createConnectedDataString($hash, $telegram_user_id)
    {
        return json_encode(['jsonrpc' => '2.0', 'method' => 'telegram.connect', "params" => ["hash" => $hash, "telegramUserId" => $telegram_user_id], "id" => 1]);
    }

    private static function isError($result, $data_string)
    {
        if (isset(self::$userID) && isset($result->error) && $result->error == '-32010') {
            RequestHelper::sendServerWork($this->userID);
        }
        $message = "Request: " . $data_string . "\r\n";
        $message .= "Answer: " . $result . "\r\n";
        $result      = json_decode($result);
        $data_string = json_decode($data_string);
        if (in_array($data_string->method, ['order.create', 'card.verify', 'order.info'])) {
            Yii::warning($message, 'api_request');
        }
        if (isset($result->error) || (isset($result->result->success) && $result->result->success == false)) {
            Yii::error($message, 'api_request');
        }
        return $result;
    }

    public function updateStatus($result)
    {
        if ($this->validateRequest($result)) {
            if ($model = Order::find()->where(['order_hash' => $result->orderHash])->one()) {
                if ($model->updateOrderStatus($result)) {
                    if ($model->save()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    public function updateCardStatus($result)
    {
        if ($this->validateCardRequest($result)) {
            if ($model = Card::find()->where(['card_number' => $result->card->number])->one()) {
                if ($model->updateCardStatus($result)) {
                    
                    if ($model->save()) {
                        return true;
                    }
                }
            }
            return false;
        }
    }
    public function updateEmailStatus($result)
    {
        if ($this->validateEmailRequest($result)) {
            if ($model = Email::find()->where(['email' => $result->email->value])->one()) {
                if ($model->updateEmailStatus($result)) {
                    if ($model->save()) {
                        return true;
                    }
                }
            }
            return false;
        }
    }
    public function updateUserDiscount($result)
    {
        if ($this->validateDiscountRequest($result)) {
            if ($model = TelegramBotUser::find()->where(['id' => $result->user->telegramUserId])->one()) {
                $model->discount = $result->user->discount;
                if ($model->save()) {
                    return true;
                }
            }
            return false;
        }
    }

    public function validateEmailRequest($result)
    {
        if (isset($result->email->value) && isset($result->email->status)) {
            return true;
        }
        return false;
    }

    public function validateCardRequest($result)
    {
        if (isset($result->card->code) && isset($result->card->number) && isset($result->card->status)) {
            return true;
        }
        return false;
    }

    private function validateDiscountRequest($result)
    {
        if (isset($result->user->telegramUserId) && isset($result->user->discount)) {
            return true;
        }
        return false;
    }
    private function validateRequest($result)
    {
        if (isset($result->orderHash) && isset($result->status) && isset($result->buy_amount) && isset($result->rate) && isset($result->recalculated)) {
            return true;
        }
        return false;
    }

    public function logRequest($result)
    {
        $message = 'Request data:';
        if (is_array($result)) {
            foreach ($result as $key => $value) {
                $message .= $key . '=>' . $value . ' ';
            }
        } else {
            $message .= $result;
        }
        Yii::warning($message, 'api_answer');
    }

}
