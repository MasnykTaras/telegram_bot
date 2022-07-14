<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_conversation}}`.
 */
class m190802_134125_create_telegram_bot_conversation_table extends Migration
{

    public $table     = '{{%telegram_bot_conversation}}';
    public $userTable = '{{%telegram_bot_user}}';
    public $chatTable = '{{%telegram_bot_chat}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'      => $this->primaryKey(),
            'user_id' => $this->integer(),
            'chat_id' => $this->integer(),
            'command' => $this->string(160),
            'notes'   => $this->text(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ]);
        $this->execute("ALTER TABLE " . $this->table . " ADD COLUMN `status` ENUM('active', 'cancelled', 'stopped')");

        $this->addForeignKey('fk_user_id_conversation', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id_conversation', $this->table, 'chat_id', $this->chatTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
        $this->dropForeignKey('fk_user_id_conversation', $this->table);
        $this->dropForeignKey('fk_chat_id_conversation', $this->table);
    }
}
