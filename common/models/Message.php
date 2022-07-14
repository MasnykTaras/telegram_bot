<?php

namespace common\models;

use Yii;
use common\models\TelegramBotUser;
use common\components\telegram\components\models\RequestHelper;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $message
 * @property int|null $is_sent
 * @property string|null $language
 */
class Message extends \yii\db\ActiveRecord
{

    const LIMIT_USERS = 500;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['is_sent'], 'integer'],
            [['message'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'message' => Yii::t('common', 'Message'),
            'is_sent' => Yii::t('common', 'Is Sent'),
            'language' => Yii::t('common', 'Language'),
        ];
    }
    public static function sendStatus()
    {
        return [0 => "No", 1 => 'Yes'];
    }
    public function sendStatusName()
    {
        $sendStatus = self::sendStatus();
        return (isset($sendStatus[$this->is_sent])) ? $sendStatus[$this->is_sent] : '';
    }
    public function sendMessage()
    {
        $users = TelegramBotUser::find()->where(['spam_allowed' => TelegramBotUser::SPAM_ALLOWED, 'language_code' => $this->language])->count();
        $cycle = $users / self::LIMIT_USERS;
        for ($i = 0; $i <= $cycle; $i++) {
            $users = TelegramBotUser::find()->where(['spam_allowed' => TelegramBotUser::SPAM_ALLOWED, 'language_code' => $this->language])->offset(self::LIMIT_USERS * $i)->limit(self::LIMIT_USERS)->all();
            if ($users) {
               
                $n = 1;
                foreach ($users as $user) {
                    if (!isset($user->telegramBotConversation) || (isset($user->telegramBotConversation) && $user->telegramBotConversation->status != 'active')) {

                        if ($n == 30) {
                            $n = 1;
                            sleep(1);
                        }

                        RequestHelper::sendSpamRequest($user->id, $this->prepareMessage($user->getUserName()));
                        $n++;
                    }
                }
            }
        }
    }
    public function prepareMessage($username)
    {   
        return str_replace('{username}', $username, $this->message);
    }
}
