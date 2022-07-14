<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property integer $currency_id
 * @property string $email
 * @property string $card_number
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $ip
 */
class BlackList extends \yii\db\ActiveRecord
{

    public $currency_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%black_list}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['currency_id'], 'required'],
            ['email', 'email'],
            [['email', 'card_number', 'ip'], 'string', 'max' => 255],
            [['updated_at', 'created_at', 'currency_id'], 'integer'],
            [['description'], 'string', 'max' => 512],
            [['ip'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('common', 'ID'),
            'currency_id'    => Yii::t('common', 'Currency'),
            'currency_ name' => Yii::t('common', 'Currency Name'),
            'card_number'    => Yii::t('common', 'Card Number'),
            'description'    => Yii::t('common', 'Description'),
            'email'          => Yii::t('common', 'E-mail'),
            'created_at'     => Yii::t('common', 'Created At'),
            'updated_at'     => Yii::t('common', 'Updated At'),
            'ip'             => Yii::t('common', 'IP'),
        ];
    }

    public function beforeSave($insert)
    {
        if (isset($this->card_number)) {
//            $currency = Currency::find()->where(['id' => $this->currency_id])->one();
//            if ($currency && $currency->code == 'BTC')
//                $this->card_number = ($this->card_number);
//            else
            $this->card_number = strtolower($this->card_number);
        }
        if (isset($this->email)) {
            $this->email = strtolower($this->email);
        }
        return parent::beforeSave($insert);
    }

    public function getCurrencyName()
    {
        return isset($this->currency_id) && ($currency = Currency::find()->where(['id' => $this->currency_id])->one()) ? $currency->name : '';
    }

    public static function getCurrencyList()
    {
        return ArrayHelper::map(Currency::find()->all(), 'id', 'name');
    }

    public static function hasBlackListIp($ip = null)
    {
        if ($ip == null) {
            $ip = Yii::$app->getRequest()->getUserIP();
            if (!$ip)
                return false;
        }
        if (($blackItem = BlackList::find()
                ->where(['ip' => $ip])
                ->one())
        ) {
            return $blackItem->id;
        }

        return false;
    }

    public static function setIp($ip)
    {
        $model     = new BlackList();
        $model->ip = $ip;
        $model->save();
    }

}
