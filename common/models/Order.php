<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\BlackList;
use common\models\ApiManager;
use common\components\telegram\components\models\RequestHelper;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $hash

 * @property string $sell_amount
 * @property string $buy_amount
 * @property string $sell_source
 * @property string $buy_target
 * @property string $payment_address
 * @property string $rate
 * @property string $old_rate
 * @property string $created_at
 * @property string $updated_at
 * @property int $sell_currency_id
 * @property int $buy_currency_id
 * @property int $status
 * @property int $sub_status
 * @property string $main_email
 * @property int $user_id
 * @property string $user_ip
 * @property int $direction_id
 * @property string $init_sell_amount
 * @property string $init_buy_amount
 * @property string $our_buy_amount
 * @property string $our_sell_amount
 *
 * @property Currency $buyCurrency
 * @property Direction $direction
 * @property Currency $sellCurrency
 * @property TelegramBotUser $user
 */
class Order extends ActiveRecord
{

    const ORDER_STATUS_ERORR     = -1;
    const ORDER_SRATUS_CANCELED  = 0;
    const ORDER_STATUS_PENDING   = 1;
    const ORDER_STATUS_RECEIVED  = 2;
    const ORDER_STATUS_HOLDED       = 3;
    const ORDER_STATUS_COMPLETE     = 4;
    const ORDER_SUB_STATUS_WAIT     = 0;
    const ORDER_SUB_STATUS_CHECKING = 1;
    const ORDER_SUB_STATUS_RECEIVED = 2;
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE   = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (isset($changedAttributes['status']) && $changedAttributes['status'] != $this->status) {
            RequestHelper::sendOrderRequest($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hash', 'sell_amount', 'buy_amount', 'rate', 'old_rate', 'sell_currency_id', 'buy_currency_id', 'status', 'init_sell_amount', 'init_buy_amount', 'our_buy_amount', 'our_sell_amount'], 'required'],
            [['sell_amount', 'buy_amount', 'rate', 'old_rate', 'init_sell_amount', 'init_buy_amount', 'our_buy_amount', 'our_sell_amount'], 'number'],
            [['sell_currency_id', 'buy_currency_id', 'status', 'sub_status', 'user_id', 'direction_id', 'recounted', 'recalculated', 'order_id'], 'integer'],
            [['hash', 'order_hash', 'sell_source', 'buy_target', 'payment_address', 'created_at', 'updated_at', 'main_email', 'user_ip'], 'string', 'max' => 255],
            [['buy_currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['buy_currency_id' => 'id']],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::className(), 'targetAttribute' => ['direction_id' => 'id']],
            [['sell_currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['sell_currency_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramBotUser::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('common', 'ID'),
            'hash'             => Yii::t('common', 'Hash'),
            'order_hash'       => Yii::t('common', 'Order Hash'),
            'sell_amount'      => Yii::t('common', 'Sell Amount'),
            'buy_amount'       => Yii::t('common', 'Buy Amount'),
            'sell_source'      => Yii::t('common', 'Sell Source'),
            'buy_target'       => Yii::t('common', 'Buy Target'),
            'payment_address'  => Yii::t('common', 'Payment Address'),
            'wallet_in'        => Yii::t('common', 'Wallet In'),
            'pay_url'          => Yii::t('common', 'Payment Url'),
            'rate'             => Yii::t('common', 'Rate'),
            'old_rate'         => Yii::t('common', 'Old Rate'),
            'created_at'       => Yii::t('common', 'Created At'),
            'updated_at'       => Yii::t('common', 'Updated At'),
            'sell_currency_id' => Yii::t('common', 'Sell Currency ID'),
            'buy_currency_id'  => Yii::t('common', 'Buy Currency ID'),
            'status'           => Yii::t('common', 'Status'),
            'sub_status'       => Yii::t('common', 'Sub Status'),
            'main_email'       => Yii::t('common', 'Main Email'),
            'user_id'          => Yii::t('common', 'User ID'),
            'chat_id'          => Yii::t('common', 'Chat ID'),
            'user_ip'          => Yii::t('common', 'User Ip'),
            'direction_id'     => Yii::t('common', 'Direction ID'),
            'order_id'         => Yii::t('common', 'Order ID'),
            'init_sell_amount' => Yii::t('common', 'Init Sell Amount'),
            'init_buy_amount'  => Yii::t('common', 'Init Buy Amount'),
            'our_buy_amount'   => Yii::t('common', 'Our Buy Amount'),
            'our_sell_amount'  => Yii::t('common', 'Our Sell Amount'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'buy_currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'sell_currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TelegramBotUser::className(), ['id' => 'user_id']);
    }

    public static function getNewCount()
    {

        return count(self::find()->where(['status' => self::ORDER_STATUS_PENDING, 'sub_status' => self::ORDER_SUB_STATUS_WAIT])->all());
    }
    public static function typeStatus()
    {
        return [
            self::ORDER_STATUS_ERORR     => Yii::t('common', 'Error'),
            self::ORDER_SRATUS_CANCELED  => Yii::t('common', 'Canceled'),
            self::ORDER_STATUS_PENDING   => Yii::t('common', 'Pending'),
            self::ORDER_STATUS_RECEIVED  => Yii::t('common', 'Received'),
            self::ORDER_STATUS_HOLDED   => Yii::t('common', 'Holded'),
            self::ORDER_STATUS_COMPLETE => Yii::t('common', 'Complete'),
        ];
    }
    public function getStatusValue($name)
    {
        $status = [
            'error'     => self::ORDER_STATUS_ERORR,
            'canceled'  => self::ORDER_SRATUS_CANCELED,
            'pending'   => self::ORDER_STATUS_PENDING,
            'received'  => self::ORDER_STATUS_RECEIVED,
            'holded'   => self::ORDER_STATUS_HOLDED,
            'complete' => self::ORDER_STATUS_COMPLETE,
        ];
        $name   = strtolower($name);
        if (isset($status[$name])) {
            return $status[$name];
        }
        return false;
    }

    public function getStatusName()
    {
        $status = self::typeStatus();
        return (isset($status[$this->status])) ? $status[$this->status] : 'none';
    }

    public static function typeStatusSub()
    {
        return [
            self::ORDER_SUB_STATUS_WAIT     => Yii::t('common', 'Wait'),
            self::ORDER_SUB_STATUS_CHECKING => Yii::t('common', 'Checking'),
            self::ORDER_SUB_STATUS_RECEIVED => Yii::t('common', 'Received'),
        ];
    }

    public function getStatusSubName()
    {
        $status = self::typeStatusSub();
        return (isset($status[$this->sub_status])) ? $status[$this->sub_status] : 'none';
    }

    public function getStatusIcone()
    {
        if ($this->status == self::ORDER_STATUS_COMPLETE) {
            return hex2bin('E29C85');
        } else if ($this->status == self::ORDER_SRATUS_CANCELED) {
            return hex2bin('E29D8C');
        }
        return hex2bin('F09F9590');
    }

    public function getOrderInfo()
    {
        $info = '';
        ($this->old_rate != $this->rate) ? $info .= hex2bin('E29D97') . Yii::t('telegram', 'The Rate was changed. The amount in the {amount} field has also changed.', ['amount' => Yii::t('telegram', 'Buy Amount')]) . PHP_EOL . Yii::t('telegram', 'Make sure it suits you.') . PHP_EOL : '';
        $info .= hex2bin('23E283A3') . ' ' . Yii::t('telegram', 'Order ID') . ' ' . $this->order_id . PHP_EOL;
        $info .= Yii::t('telegram', 'Status') . ' ' . $this->statusIcone . ' ' . $this->statusName . PHP_EOL;
        $info .= hex2bin('E2AC85') . ' ' . Yii::t('telegram', 'Sell Amount') . ' ' . $this->sell_amount . PHP_EOL;
        $info .= hex2bin('E29EA1') . ' ' . Yii::t('telegram', 'Buy Amount') . ' ' . $this->buy_amount . PHP_EOL;
        ($this->buy_amount != $this->init_buy_amount) ? $info .= Yii::t('telegram', 'Old Buy Amount') . ' ' . $this->init_buy_amount . PHP_EOL : '';
        $info .= Yii::t('telegram', 'Sell Source') . ' ' . $this->sell_source . PHP_EOL;
        $info .= Yii::t('telegram', 'Buy Target') . ' ' . $this->buy_target . PHP_EOL;

        (!empty($this->wallet_in)) ? $info .= Yii::t('telegram', 'Payment Address') . ' ' . $this->wallet_in . PHP_EOL : '';
        (!empty($this->pay_url)) ? $info .= Yii::t('telegram', 'Payment Url') . ' ' . $this->pay_url . PHP_EOL : '';

        $info .= Yii::t('telegram', 'Rate') . ' ' . $this->rate . PHP_EOL;
        ($this->old_rate != $this->rate) ? $info .= Yii::t('telegram', 'Old Rate') . ' ' . $this->old_rate . PHP_EOL : '';
        $info .= hex2bin('F09F9590') . ' ' . Yii::t('telegram', 'Created At') . ' ' . Yii::$app->formatter->asDate($this->created_at, 'long') . PHP_EOL;
        $info .= hex2bin('F09F93AB') . ' ' . Yii::t('telegram', 'Email') . ' ' . $this->main_email . PHP_EOL;

        $info .= hex2bin('F09F9497') . ' ' . Yii::t('telegram', 'Status page on 365cash.co') . ' ' . $this->statusLink . PHP_EOL;
        $info .= Yii::t('telegram', 'PAY FOR THE BANK INFORMATION SPECIFIED ABOVE') . PHP_EOL;
        return $info;
    }

    public function getShortOrderInfo()
    {

        return $this->statusIcone . ' ' . Yii::$app->formatter->asDate($this->created_at, 'long') . ' ' . '#' . $this->order_id . ' ' . $this->sellCurrency->name . ' > ' . $this->buyCurrency->name;
    }
    public function getStatusLink()
    {
        if ($this->user->language == 'ru' || $this->user->language == 'uk') {
            $link = "https://365cash.co/order/status?id=";
        } else {
            $link = "https://365cash.co/en/order/status?id=";
        }
        return $link . $this->order_hash;
    }

    public function addNewOrder($user_id, $preOrder)
    {
        $answer = false;
        if ($user_id && $preOrder) {

            $this->loadOrderValue($preOrder);
            if ($this->validate()) {
                if (!$this->blackListValidate()) {
                    $answer['fatalError'] = true;
                    $answer['result']  = false;
                    $answer['message']    = hex2bin('F09F9AA8') . ' ' . Yii::t('telegram', 'You are on our blacklists.') . PHP_EOL;
                } else {
                    $answer = $this->addWalletInfo($preOrder);
                    if ($answer['result']) {
                        if (!$this->save()) {
                            $answer['result']  = false;
                            $answer['message'] = Yii::t('telegram', 'The order didn\'t create. Please try later. Take our apologies.') . PHP_EOL;
                        }
                    }
                }
            }
        }
        return $answer;
    }

    public function loadOrderValue($preOrder)
    {
        $this->hash = Yii::$app->getSecurity()->generateRandomString();

        $this->sell_amount = $preOrder->sell_amount;
        $this->main_email  = $preOrder->main_email;
        $this->buy_target  = $preOrder->buy_wallet;
        $this->sell_source = $preOrder->sell_wallet;
        $this->chat_id     = $preOrder->chat_id;
        $this->user_id     = $preOrder->user_id;
        $this->user_ip      = $preOrder->user_ip;
        $this->direction_id = $preOrder->direction_id;

        $this->rate             = $preOrder->rate;
        $this->old_rate         = $preOrder->rate;
        $this->sell_currency_id = $this->direction->sell_currency_id;
        $this->buy_currency_id  = $this->direction->buy_currency_id;
        $this->status           = self::ORDER_STATUS_PENDING;
        $this->sub_status       = self::ORDER_SUB_STATUS_WAIT;

        $this->init_sell_amount = $this->sell_amount;
        $this->buy_amount       = $preOrder->direction->recalculateAmount($this->sell_amount, false, $this->rate, false);
        $this->init_buy_amount  = $this->buy_amount;
        $this->our_sell_amount = $this->sell_amount;
        $this->our_buy_amount   = $this->buy_amount;
    }
    public function addWalletInfo($preOrder)
    {
        $model = new ApiManager($this->user_id);

        ($this->user->language == 'en') ? $language = $this->user->language : $language = 'ru';
        $result   = $model->createPreorder($this->createParamString($preOrder), $this->direction->directionName, $this->hash, $language, $this->user_id, $this->user_ip);

        if (isset($result->result)) {
            if ($result->result->success == 'ture') {
                $this->buy_amount   = $result->result->info->buy_amount;
                $this->rate         = $result->result->info->rate;
                $this->order_hash   = $result->result->info->orderHash;
                $this->order_id     = $result->result->info->order_id;
                $this->recalculated = ($result->result->info->recalculated == 'true') ? 1 : 0;

                if ($this->getStatusValue($result->result->info->status)) {
                    $this->status     = $this->getStatusValue($result->result->info->status);
                    $answer['result'] = true;
                } else {
                    $answer['result'] = false;
                }
                if (isset($result->result->info->paymentDetails->walletIn)) {
                    $this->wallet_in  = $result->result->info->paymentDetails->walletIn;
                    $answer['result'] = true;
                } else if (isset($result->result->info->paymentDetails->payUrl)) {
                    $this->pay_url    = $result->result->info->paymentDetails->payUrl;
                    $answer['result'] = true;
                } else {
                    $answer['result'] = false;
                }
            } else {
                
                return $this->getErrorAnswer($result->result);
            }
        } else {
            $answer['result'] = false;
        }
        $answer['fatalError'] = false;
        if (!$answer['result']) {
            $answer['message'] = Yii::t('telegram', 'The order didn\'t create. Please try later. Take our apologies.') . PHP_EOL;
        }
        return $answer;
    }
    public function getErrorAnswer($result)
    {
        $answer['fatalError'] = false;
        $answer['result']  = false;
        $answer['message']    = Yii::t('telegram', 'The order didn\'t create. Please try later. Take our apologies.') . PHP_EOL;

        if (isset($result->errors)) {
            
            if (isset($result->errors->buy_amount) && isset($result->errors->buy_amount->error)) {
                $answer['fatalError'] = true;
                $answer['message']    = Yii::t('telegram', 'Please accept our apologies, but we have a problem with {error}', ['error' => $result->errors->buy_amount->error]) . PHP_EOL;
            } else if (isset($result->errors->sell_amount) && isset($result->errors->sell_amount->error)) {
                $answer['fatalError'] = true;
                if ($result->errors->sell_amount->errorCode == 7) {
                    $answer['message'] = Yii::t('telegram', 'Sales amount must be less than {amount} {name}', ['amount' => (float) $this->direction->maxSellAmount, 'name' => $this->sellCurrency->name]) . PHP_EOL;
                }
            } else {
                $answer['message'] = Yii::t('telegram', 'The order didn\'t create. Please try later. Take our apologies.') . PHP_EOL;
            }
        }
        return $answer;
    }

    public function createParamString($preOrder)
    {
        $fields = json_decode($this->direction->direction_fields);
        $params = [];
        foreach ($fields as $field) {
            $name = $field->name;
            if (!is_null($preOrder->$name)) {
                $params[$name] = $preOrder->$name;
            }
        }
        return $params;
    }
    public function updateOrderStatus($result)
    {
        if ($this->getStatusValue($result->status) || $this->getStatusValue($result->status) == 0) {
            $this->status = $this->getStatusValue($result->status);
        } else {
            return false;
        }
        $this->sell_amount  = $result->sell_amount;
        $this->buy_amount   = $result->buy_amount;
        $this->rate         = $result->rate;
        $this->recalculated = ($result->recalculated == 'true') ? 1 : 0;
        return true;
    }
    public function blackListValidate()
    {
        $query = BlackList::find();

        if ($this->main_email) {
            $query->where(['email' => $this->main_email]);
        }

        if ($this->sell_source) {
            $query->orWhere(['currency_id' => $this->sell_currency_id, 'card_number' => strtolower($this->sell_source)]);
            $query->orWhere(['currency_id' => $this->buy_currency_id, 'card_number' => strtolower($this->sell_source)]);
        }

        if ($this->buy_target) {
            $query->orWhere(['currency_id' => $this->sell_currency_id, 'card_number' => strtolower($this->buy_target)]);
            $query->orWhere(['currency_id' => $this->buy_currency_id, 'card_number' => strtolower($this->buy_target)]);
        }

        $model = $query->one();
        
        if (isset($model) && $model != null) {
            return false;
        }
        return true;
    }
    public static function checkPendingOrder($order_hash)
    {
        $model = new ApiManager();
        return $model->checkOrdersInfo($order_hash);
    }

}
