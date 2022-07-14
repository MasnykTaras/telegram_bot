<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "telegram_bot_chat".
 *
 * @property int $id
 * @property string $title
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property int $all_members_are_administrators
 * @property string $created_at
 * @property string $updated_at
 * @property int $old_id
 * @property string $type
 *
 * @property TelegramBotConversation[] $telegramBotConversations
 * @property TelegramBotEditedMessage[] $telegramBotEditedMessages
 * @property TelegramBotMessage[] $telegramBotMessages
 * @property TelegramBotMessage[] $telegramBotMessages0
 * @property TelegramBotUserChat[] $telegramBotUserChats
 */
class TelegramBotChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_bot_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['all_members_are_administrators', 'old_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string'],
            [['title', 'username', 'first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'username' => Yii::t('common', 'Username'),
            'first_name' => Yii::t('common', 'First Name'),
            'last_name' => Yii::t('common', 'Last Name'),
            'all_members_are_administrators' => Yii::t('common', 'All Members Are Administrators'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'old_id' => Yii::t('common', 'Old ID'),
            'type' => Yii::t('common', 'Type'),
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramBotUserChats()
    {
        return $this->hasMany(TelegramBotUserChat::className(), ['chat_id' => 'id']);
    }

}
