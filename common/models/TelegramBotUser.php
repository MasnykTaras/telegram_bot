<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use common\components\telegram\components\models\RequestHelper;


/**
 * This is the model class for table "telegram_bot_user".
 *
 * @property int $id
 * @property int $is_bot
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $language_code
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TelegramBotCallbackQuery[] $telegramBotCallbackQueries
 * @property TelegramBotChosenInlineResult[] $telegramBotChosenInlineResults
 * @property TelegramBotConversation[] $telegramBotConversations
 * @property TelegramBotEditedMessage[] $telegramBotEditedMessages
 * @property TelegramBotInlineQuery[] $telegramBotInlineQueries
 * @property TelegramBotMessage[] $telegramBotMessages
 * @property TelegramBotMessage[] $telegramBotMessages0
 * @property TelegramBotMessage[] $telegramBotMessages1
 * @property TelegramBotPreCheckoutQuery[] $telegramBotPreCheckoutQueries
 * @property TelegramBotShippingQuery[] $telegramBotShippingQueries
 * @property TelegramBotUserChat[] $telegramBotUserChats
 */
class TelegramBotUser extends ActiveRecord
{
    const CONNECTED_FALSE = 0;
    const CONNECTED_TRUE  = 1;
    const SPAM_DISABLE = 0;
    const SPAM_ALLOWED = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_bot_user';
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
                'value'      => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
       
        if (isset($changedAttributes['discount']) && $changedAttributes['discount'] != $this->discount) {
            RequestHelper::sendDiscountRequest($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_bot', 'is_connected', 'spam_allowed'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['discount'], 'number'],
            [['first_name', 'last_name', 'username', 'hash'], 'string', 'max' => 255],
            [['language_code', 'preferred_language'], 'string', 'max' => 10],
            ['discount', 'filter', 'filter' => function($value) {
                    return (float) $value;
                }]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('common', 'ID'),
            'hash'          => Yii::t('common', 'Hash'),
            'is_bot'        => Yii::t('common', 'Is Bot'),
            'is_connected'  => Yii::t('common', 'Is Connected'),
            'discount'      => Yii::t('common', 'Discount'),
            'first_name'    => Yii::t('common', 'First Name'),
            'last_name'     => Yii::t('common', 'Last Name'),
            'username'      => Yii::t('common', 'Username'),
            'spam_allowed'  => Yii::t('common', 'Spam Allowed'),
            'language_code'      => Yii::t('common', 'Language Code'),
            'preferred_language' => Yii::t('common', 'Preferred language'),
            'created_at'    => Yii::t('common', 'Created At'),
            'updated_at'    => Yii::t('common', 'Updated At'),
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramBotUserChat()
    {
        return $this->hasOne(TelegramBotChat::className(), ['user_id' => 'id']);
    }
    public function getTelegramBotConversation()
    {
        return $this->hasOne(TelegramBotConversation::className(), ['user_id' => 'id']);
    }

    public function getLanguage()
    {
        return $this->language_code;
    }

    public static function userLanguage($id)
    {
        
        if ($model = self::find()->where(['id' => $id])->one()) {
            if (isset($model->preferred_language)) {
                return $model->preferred_language;
            } else if (isset($model->language_code)) {
                return $model->language_code;
            }
        }
        return 'ru';
    }
     public function getConectedStatusName()
    {
        $status = self::conectedStatus();
        return (isset($status[$this->is_connected])) ? $status[$this->is_connected] : 'none';
    }

    public static function conectedStatus()
    {
        return [
            self::CONNECTED_FALSE => Yii::t('common', 'False'),
            self::CONNECTED_TRUE  => Yii::t('common', 'True'),
        ];
    }
    public static function getUserDicsount($ID)
    {
        if ($model = self::find()->where(['id' => $ID])->one()) {
            return $model->discount;
        }
        return false;
    }

    public static function isConnected($ID)
    {
        if (self::find()->where(['id' => $ID, 'is_connected' => self::CONNECTED_TRUE])->one()) {
            return true;
        }
        return false;
    }
    public function getUserName()
    {
        return (isset($this->username)) ? $this->username : (isset($this->first_name)) ? $this->first_name : $name = '';
    }

    public static function spamStatus()
    {
        return [0 => "No", 1 => 'Yes'];
    }

    public function spamStatusName()
    {
        $sendStatus = self::spamStatus();
        return (isset($sendStatus[$this->spam_allowed])) ? $sendStatus[$this->spam_allowed] : '';
    }
    

}
