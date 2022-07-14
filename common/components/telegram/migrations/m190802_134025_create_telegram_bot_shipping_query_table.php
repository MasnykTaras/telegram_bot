<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_shipping_query}}`.
 */
class m190802_134025_create_telegram_bot_shipping_query_table extends Migration
{

    public $table     = '{{%telegram_bot_shipping_query}}';
    public $userTable = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'               => $this->primaryKey(),
            'user_id'          => $this->integer(),
            'invoice_payload'  => $this->string(255)->notNull(),
            'shipping_address' => $this->string(255)->notNull(),
            'created_at'       => $this->timestamp()->defaultValue(null),
        ]);
        $this->addForeignKey('fk_user_id_shipping_query', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_id_shipping_query', $this->table);
        $this->dropTable($this->table);
    }
}
