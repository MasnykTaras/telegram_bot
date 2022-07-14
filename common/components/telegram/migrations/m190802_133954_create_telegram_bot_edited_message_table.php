<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_edited_message}}`.
 */
class m190802_133954_create_telegram_bot_edited_message_table extends Migration
{

    public $table        = '{{%telegram_bot_edited_message}}';
    public $userTable    = '{{%telegram_bot_user}}';
    public $chatTable    = '{{%telegram_bot_chat}}';
    public $messageTable = '{{%telegram_bot_message}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'chat_id'    => $this->integer(),
            'message_id' => $this->integer(),
            'user_id'    => $this->integer(),
            'edit_date'  => $this->timestamp()->defaultValue(null),
            'text'       => $this->text(),
            'entities'   => $this->text(),
            'caption'    => $this->text(),
        ]);
        $this->addForeignKey('fk_user_id_editer_message', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id_editer_message', $this->table, 'chat_id', $this->chatTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id_and_message_id_editer_message', $this->table, ['chat_id', 'message_id'], $this->messageTable, ['chat_id', 'id'], 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_chat_id_and_message_id_editer_message', $this->table);
        $this->dropForeignKey('fk_chat_id_editer_message', $this->table);
        $this->dropForeignKey('fk_user_id_editer_message', $this->table);
        $this->dropTable('{{%telegram_bot_edited_message}}');
    }
}
