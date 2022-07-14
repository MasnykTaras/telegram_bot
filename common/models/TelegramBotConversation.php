<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "telegram_bot_conversation".
 *
 * @property int $id
 * @property int $user_id
 * @property int $chat_id
 * @property string $command
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 *
 * @property TelegramBotChat $chat
 * @property TelegramBotUser $user
 */
class TelegramBotConversation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_bot_conversation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'chat_id'], 'integer'],
            [['notes', 'status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['command'], 'string', 'max' => 160],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramBotChat::className(), 'targetAttribute' => ['chat_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramBotUser::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'chat_id' => Yii::t('common', 'Chat ID'),
            'command' => Yii::t('common', 'Command'),
            'notes' => Yii::t('common', 'Notes'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'status' => Yii::t('common', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(TelegramBotChat::className(), ['id' => 'chat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TelegramBotUser::className(), ['id' => 'user_id']);
    }
}
