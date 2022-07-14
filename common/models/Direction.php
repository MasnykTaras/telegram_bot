<?php

namespace common\models;

use Yii;
use common\models\TelegramBotUser;

/**
 * This is the model class for table "direction".
 *
 * @property int $id
 * @property int $sell_currency_id
 * @property int $buy_currency_id
 * @property string $rate
 * @property int $main_currency
 * @property int $status
 * @property string $min_sell
 * @property string $min_buy
 * @property string $max_sell
 * @property string $max_buy
 *
 * @property Currency $buyCurrency
 * @property Currency $mainCurrency
 * @property Currency $sellCurrency
 * @property Order[] $orders
 */
class Direction extends \yii\db\ActiveRecord
{

    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE   = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sell_currency_id', 'buy_currency_id', 'rate', 'main_currency', 'min_sell', 'min_buy', 'max_sell', 'max_buy'], 'required'],
            [['sell_currency_id', 'buy_currency_id', 'main_currency', 'status'], 'integer'],
            [['rate', 'min_sell', 'min_buy', 'max_sell', 'max_buy'], 'number'],
            [['direction_fields'], 'string'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['buy_currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['buy_currency_id' => 'id']],
            [['main_currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['main_currency' => 'id']],
            [['sell_currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['sell_currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('common', 'ID'),
            'sell_currency_id' => Yii::t('common', 'Sell Currency ID'),
            'buy_currency_id'  => Yii::t('common', 'Buy Currency ID'),
            'rate'             => Yii::t('common', 'Rate'),
            'main_currency'    => Yii::t('common', 'Main Currency'),
            'status'           => Yii::t('common', 'Status'),
            'min_sell'         => Yii::t('common', 'Min Sell'),
            'min_buy'          => Yii::t('common', 'Min Buy'),
            'max_sell'         => Yii::t('common', 'Max Sell'),
            'max_buy'          => Yii::t('common', 'Max Buy'),
            'direction_fields' => Yii::t('common', 'Direction Fields'),
        ];
    }
    public function getCurrentRate($userID)
    {
        $rate     = $this->rate;
        
        if ($discount = TelegramBotUser::getUserDicsount($userID)) {
           
            if ($this->isMainCurrencySell()) {
                $rate = $rate / (1 - $discount);
            } else {
                $rate = $rate * (1 - $discount);
            }
        }
        
        return $rate;
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
    public function getMainCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'main_currency']);
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
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['direction_id' => 'id']);
    }

    public function getDirectionFullName()
    {
        return $this->sellCurrency->name . ' ' . hex2bin('E29EA1') . ' ' . $this->buyCurrency->name;
    }

    public function getDirectionName()
    {
        return $this->sellCurrency->code . '_' . $this->buyCurrency->code;
    }
    public function isMainCurrencySell()
    {
        return $this->sellCurrency->id == $this->mainCurrency->id;
    }

    public function getMainCurrentID($code)
    {
        if ($model = Currency::find()->where(['code' => $code])->one()) {
            return $model->id;
        }
        return false;
    }

    public function getCurrentUserRate()
    {
        return $this->rate;
    }

    public static function directionStatus()
    {
        return [
            self::STATUS_DISABLED => Yii::t('common', 'Disabled'),
            self::STATUS_ACTIVE   => Yii::t('common', 'Active'),
        ];
    }

    public function getStatusName()
    {
        $status = self::directionStatus();

        return (isset($status[$this->status])) ? $status[$this->status] : 'none';
    }

    public function recalculateAmount($sell_amount = false, $buy_amount = false, $rate = false, $precisionSell = true)
    {
        $rate = $rate ? $rate : $this->rate;
        if ($sell_amount) {
            if ($this->isMainCurrencySell()) {
                $value = $sell_amount * $rate;
            } else {
                $value = $sell_amount / $rate;
            }
        }
        if ($buy_amount) {
            if ($this->isMainCurrencySell()) {
                $value = $buy_amount / $rate;
            } else {
                $value = $buy_amount * $rate;
            }
        }
        
        if ($precisionSell) {
            $precision = $this->sellCurrency->precision;
        } else {
            $precision = $this->buyCurrency->precision;
        }

        $precision = $precision ?: Currency::PRECISION;
        return round($value, $precision);
    }
    public function shortInfo($userID)
    {
        if ($this->isMainCurrencySell()) {
            $rate = $this->buyCurrency->name . '(' . $this->buyCurrency->code . ')/1:' . (float) $this->getCurrentRate($userID) . '/' . (float) $this->buyCurrency->reserve;
        } else {
            $rate = $this->buyCurrency->name . '(' . $this->buyCurrency->code . ')/' . (float) $this->getCurrentRate($userID) . ':1/' . (float) $this->buyCurrency->reserve;
        }
        return $rate;
    }

    public function convertToNumber($value)
    {
        $pattern = '/^\d+\,\d+$/';
        if (preg_match($pattern, $value)) {
            $value = str_replace(',', '.', $value);
        }

        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    public function getMinSellAmount()
    {
        return min($this->min_sell, $this->recalculateAmount(false, $this->min_buy));
    }

    public function getMaxSellAmount()
    {
        return min($this->max_sell, $this->maxSellReducedAmount);
    }
     public function getMaxSellReducedAmount()
    {
        $maxBuyRecalculateAmount = $this->recalculateAmount($this->max_sell);
        if ($this->buyCurrency->reserve < $maxBuyRecalculateAmount) {

            return min($this->recalculateAmount(false, $this->max_buy), $this->recalculateAmount(false, $this->buyCurrency->reserve));
        }
        return $this->max_sell;
    }

    public function getMinBuyAmount()
    {
        return min($this->min_buy, $this->recalculateAmount($this->min_sell));
    }

    public function getMaxBuyAmount()
    {
        return min($this->max_buy, $this->recalculateAmount($this->max_sell));
    }

   

}
