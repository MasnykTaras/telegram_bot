<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_inline_query}}`.
 */
class m190802_133815_create_telegram_bot_inline_query_table extends Migration
{
    public $table     = '{{%telegram_bot_inline_query}}';
    public $userTable = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(),
            'location'   => $this->string(),
            'query'      => $this->text(),
            'offset'     => $this->string(255),
            'created_at' => $this->timestamp()->defaultValue(null),
        ]);
        $this->addForeignKey('fk_user_id_inline_query', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_id_inline_query', $this->table);
        $this->dropTable($this->table);
    }

}
