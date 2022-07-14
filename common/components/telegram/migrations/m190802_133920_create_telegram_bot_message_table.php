<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_message}}`.
 */
class m190802_133920_create_telegram_bot_message_table extends Migration
{

    public $table     = '{{%telegram_bot_message}}';
    public $userTable = '{{%telegram_bot_user}}';
    public $chatTable = '{{%telegram_bot_chat}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                      => $this->primaryKey(),
            'chat_id'                 => $this->integer(),
            'user_id'                 => $this->integer(),
            'date'                    => $this->timestamp()->defaultValue(null),
            'forward_from'            => $this->integer(),
            'forward_from_chat'       => $this->integer(),
            'forward_from_message_id' => $this->integer(),
            'forward_signature'       => $this->text(),
            'forward_sender_name'     => $this->text(),
            'forward_date'            => $this->timestamp()->defaultValue(null),
            'reply_to_chat'           => $this->integer(),
            'reply_to_message'        => $this->integer(),
            'edit_date'               => $this->integer(),
            'media_group_id'          => $this->text(),
            'author_signature'        => $this->text(),
            'text'                    => $this->text(),
            'entities'                => $this->text(),
            'caption_entities'        => $this->text(),
            'audio'                   => $this->text(),
            'document'                => $this->text(),
            'animation'               => $this->text(),
            'game'                    => $this->text(),
            'photo'                   => $this->text(),
            'sticker'                 => $this->text(),
            'video'                   => $this->text(),
            'voice'                   => $this->text(),
            'video_note'              => $this->text(),
            'caption'                 => $this->text(),
            'contact'                 => $this->text(),
            'location'                => $this->text(),
            'venue'                   => $this->text(),
            'poll'                    => $this->text(),
            'new_chat_members'        => $this->text(),
            'left_chat_member'        => $this->integer(),
            'new_chat_title'          => $this->string(255),
            'new_chat_photo'          => $this->text(),
            'delete_chat_photo'       => $this->tinyInteger(2)->defaultValue(0),
            'group_chat_created'      => $this->tinyInteger(2)->defaultValue(0),
            'supergroup_chat_created' => $this->tinyInteger(2)->defaultValue(0),
            'channel_chat_created'    => $this->tinyInteger(2)->defaultValue(0),
            'migrate_to_chat_id'      => $this->integer(),
            'migrate_from_chat_id'    => $this->integer(),
            'pinned_message'          => $this->text(),
            'invoice'                 => $this->text(),
            'successful_payment'      => $this->text(),
            'connected_website'       => $this->text(),
            'passport_data'           => $this->text(),
            'reply_markup'            => $this->text(),
        ]);
        $this->addForeignKey('fk_user_id_message', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id_message', $this->table, 'chat_id', $this->chatTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_forward_from_message', $this->table, 'forward_from', $this->userTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_forward_from_chat_message', $this->table, 'forward_from_chat', $this->chatTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_reply_to_chat_and_reply_to_message_message', $this->table, ['reply_to_chat', 'reply_to_message'], $this->table, ['chat_id', 'id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_left_chat_member_message', $this->table, 'forward_from_chat', $this->userTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_left_chat_member_message', $this->table);
        $this->dropForeignKey('fk_reply_to_chat_and_reply_to_message_message', $this->table);
        $this->dropForeignKey('fk_forward_from_chat_message', $this->table);
        $this->dropForeignKey('fk_forward_from_message', $this->table);
        $this->dropForeignKey('fk_chat_id_message', $this->table);
        $this->dropForeignKey('fk_user_id_message', $this->table);
        $this->dropTable($this->table);
    }
}
