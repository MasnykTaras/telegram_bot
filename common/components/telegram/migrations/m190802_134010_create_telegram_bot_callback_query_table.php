<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_callback_query}}`.
 */
class m190802_134010_create_telegram_bot_callback_query_table extends Migration
{

    public $table        = '{{%telegram_bot_callback_query}}';
    public $userTable    = '{{%telegram_bot_user}}';
    public $messageTable = '{{%telegram_bot_message}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                => $this->primaryKey(),
            'user_id'           => $this->integer(),
            'chat_id'           => $this->integer(),
            'message_id'        => $this->integer(),
            'inline_message_id' => $this->string(255),
            'chat_instance'     => $this->string(255),
            'data'              => $this->string(255),
            'game_short_name'   => $this->string(255),
            'created_at'        => $this->timestamp()->defaultValue(null),
        ]);
        $this->addForeignKey('fk_user_id_callback_query', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id_callback_query', $this->table, ['chat_id', 'message_id'], $this->messageTable, ['chat_id', 'id'], 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_id_callback_query', $this->table);
        $this->dropForeignKey('fk_chat_id_callback_query', $this->table);
        $this->dropTable($this->table);
    }
}
