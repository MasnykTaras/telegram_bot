<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $name
 * @property string $reserve
 * @property string $code
 * @property string $buy_commission
 * @property string $sell_commission
 * @property int $apply_commission_on_sell
 * @property int $apply_commission_on_buy
 * @property int $status
 * @property string $symbol
 * @property string $placeholder
 * @property string $regular
 * @property string $iso_code
 * @property string $card_number
 * @property int $parent_id
 * @property array $sell_fields
 * @property array $buy_fields
 * @property int $type
 *
 * @property Direction[] $directions
 * @property Direction[] $directions0
 * @property Direction[] $directions1
 * @property Order[] $orders
 * @property Order[] $orders0
 */
class Currency extends \yii\db\ActiveRecord
{

    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE   = 1;
    const DEFAULT_PRECISION = 8;
    const QIWI_CODE         = 'QWRUB';
    const EXMO_CODE         = 'EXMUSD';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code', 'reserve'], 'required'],
            [['reserve', 'buy_commission', 'sell_commission'], 'number'],
            [['apply_commission_on_sell', 'apply_commission_on_buy', 'status', 'parent_id', 'type', 'precision', 'card_validation', 'email_validation', 'ip_validation'], 'integer'],
            [['sell_fields', 'buy_fields'], 'safe'],
            [['name', 'code', 'symbol', 'placeholder', 'regular', 'iso_code', 'card_number'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['precision'], 'default', 'value' => self::DEFAULT_PRECISION],
            [['card_validation'], 'default', 'value' => self::STATUS_DISABLED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                       => Yii::t('common', 'ID'),
            'name'                     => Yii::t('common', 'Name'),
            'reserve'                  => Yii::t('common', 'Reserve'),
            'code'                     => Yii::t('common', 'Code'),
            'buy_commission'           => Yii::t('common', 'Buy Commission'),
            'sell_commission'          => Yii::t('common', 'Sell Commission'),
            'apply_commission_on_sell' => Yii::t('common', 'Apply Commission On Sell'),
            'apply_commission_on_buy'  => Yii::t('common', 'Apply Commission On Buy'),
            'status'                   => Yii::t('common', 'Status'),
            'symbol'                   => Yii::t('common', 'Symbol'),
            'placeholder'              => Yii::t('common', 'Placeholder'),
            'regular'                  => Yii::t('common', 'Regular'),
            'iso_code'                 => Yii::t('common', 'Iso Code'),
            'card_number'              => Yii::t('common', 'Card Number'),
            'parent_id'                => Yii::t('common', 'Parent ID'),
            'sell_fields'              => Yii::t('common', 'Sell Fields'),
            'buy_fields'               => Yii::t('common', 'Buy Fields'),
            'type'                     => Yii::t('common', 'Type'),
            'precision'                => Yii::t('common', 'Precision'),
            'card_validation'          => Yii::t('common', 'Card Validation'),
            'email_validation'         => Yii::t('common', 'Email Validation'),
            'ip_validation'            => Yii::t('common', 'IP Validation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyDirections()
    {
        return $this->hasMany(Direction::className(), ['buy_currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainDirections()
    {
        return $this->hasMany(Direction::className(), ['main_currency' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellDirections()
    {
        return $this->hasMany(Direction::className(), ['sell_currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['buy_currency_id' => 'id']);
    }
    /**
     * Перебирає масив і присвоює занченн атрибуту якщо такий атрибут існує
     * @param type array
     */
    public function preLoad($currency)
    {

        foreach ($currency as $key => $value) {
            if ($this->hasAttribute($key)) {
                $this->$key = $value;
            }
        }
    }
    public static function currencyStatus()
    {
        return [
            self::STATUS_DISABLED => Yii::t('backend', 'Disabled'),
            self::STATUS_ACTIVE   => Yii::t('backend', 'Active'),
        ];
    }

    public function getStatusName()
    {
        $status = self::currencyStatus();
        return (isset($status[$this->status])) ? $status[$this->status] : 'none';
    }
    public function isHaveSellDirections()
    {
        $count = Direction::find()->where(['sell_currency_id' => $this->id])->count();
       
        if ($count > 0) {
            return true;
        }
        return false;
    }
    public static function getChildrenCode($parentCode)
    {
        $parentID = self::find()->where(['code' => $parentCode])->one();
        $models   = self::find()->where(['parent_id' => $parentID])->all();
        return ArrayHelper::getColumn($models, 'code');
    }
    public static function getAllCurrencies()
    {
        return self::find()->all();
    }

}
