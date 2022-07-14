<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_user_chat}}`.
 */
class m190802_133750_create_telegram_bot_user_chat_table extends Migration
{

    public $table     = '{{%telegram_bot_user_chat}}';
    public $userTable = '{{%telegram_bot_user}}';
    public $chatTable = '{{%telegram_bot_chat}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'user_id' => $this->integer(),
            'chat_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk_user_id', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id', $this->table, 'chat_id', $this->chatTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_chat_id', $this->table);
        $this->dropForeignKey('fk_user_id', $this->table);
        $this->dropTable($this->table);
    }
}
