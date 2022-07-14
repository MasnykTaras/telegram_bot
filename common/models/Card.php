<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\components\telegram\components\models\RequestHelper;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string $card_number
 * @property int $status
 * @property int $sistem
 * @property string $created_at
 * @property int $user_id
 */
class Card extends \yii\db\ActiveRecord
{
    const CARD_STATUS_NEW       = 0;
    const CARD_STATUS_PENDING   = 1;
    const CARD_STATUS_CONFIRMED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if (isset($changedAttributes['status'])) {
            RequestHelper::sendCardRequest($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['card_number'], 'required'],
            [['status', 'system', 'user_id'], 'integer'],
            [['card_number', 'created_at', 'code'], 'string', 'max' => 255],
            [['card_number'], 'unique'],
            [['system', 'card_number'], 'filter', 'filter' => 'strip_tags'],
            [['system', 'card_number'], 'filter', 'filter' => 'nl2br'],
            [['status'], 'default', 'value' => self::CARD_STATUS_NEW]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'card_number' => Yii::t('common', 'Card Number'),
            'status' => Yii::t('common', 'Status'),
            'system'      => Yii::t('common', 'System'),
            'created_at' => Yii::t('common', 'Created At'),
            'user_id' => Yii::t('common', 'User ID'),
        ];
    }

    public static function cardStatus()
    {
        return [
            
            self::CARD_STATUS_NEW      => Yii::t('common', 'New'),
            self::CARD_STATUS_PENDING   => Yii::t('common', 'Pending'),
            self::CARD_STATUS_CONFIRMED => Yii::t('common', 'Confirmed'),
        ];
    }

    public function getStatusName()
    {
        $status = self::cardStatus();
        return (isset($status[$this->status])) ? $status[$this->status] : 'none';
    }

    public function getStatusValue($name)
    {
        $status = [
            'new'       => self::CARD_STATUS_NEW,
            'pending'   => self::CARD_STATUS_PENDING,
            'confirmed' => self::CARD_STATUS_CONFIRMED
        ];
        $name   = strtolower($name);
        if (isset($status[$name])) {
            return $status[$name];
        }
        return false;
    }

    public static function addNewCard($card_number, $user_id, $code)
    {
        $model              = new Card();
        $model->card_number = $card_number;
        $model->user_id     = $user_id;
        $model->code        = $code;

        $result = $model->sendApiRequest($card_number, $code);

        if ($result) {
            if ($model->getStatusValue($result->card->status) || $model->getStatusValue($result->card->status) == 0) {
                $model->status = $model->getStatusValue($result->card->status);
            }
        }
        if ($model->save()) {
            return $model;
        }
        return false;
    }
    private function sendApiRequest($card_number, $code)
    {
        $ApiManager = new ApiManager($this->user_id);
        $result     = $ApiManager->createCardValidate($card_number, $code);

        if (isset($result->result)) {
            if ($ApiManager->validateCardRequest($result->result)) {
                return $result->result;
            }
        }
        return false;
    }

    public static function getNewCount()
    {

        return count(self::find()->where(['status' => self::CARD_STATUS_NEW])->all());
    }
    public static function isCardConfirm($card_number)
    {
        return self::find()->where(['card_number' => $card_number, 'status' => self::CARD_STATUS_CONFIRMED])->one();
    }

    public static function checkingCard($card_number, $user_id, $code)
    {
        $card = self::find()->where(['card_number' => $card_number])->one();
        if ($card) {
            if ($card->status != self::CARD_STATUS_CONFIRMED) {
                $card->updateCardStatus($card->sendApiRequest($card->card_number, $card->code));
            }
        } else {
            $card = self::addNewCard($card_number, $user_id, $code);
            if (!$card) {
                return false;
            }
        }
        if ($card->status == self::CARD_STATUS_CONFIRMED) {
            return true;
        }
        return false;
    }

    public function updateCardStatus($result)
    {
        if (isset($result->card)) {
            if ($this->getStatusValue($result->card->status)) {
                $this->status = $this->getStatusValue($result->card->status);
                return true;
            }
        }
        return false;
    }

}
