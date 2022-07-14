<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_pre_checkout_query}}`.
 */
class m190802_134042_create_telegram_bot_pre_checkout_query_table extends Migration
{

    public $table     = '{{%telegram_bot_pre_checkout_query}}';
    public $userTable = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                 => $this->primaryKey(),
            'user_id'            => $this->integer(),
            'currency'           => $this->string(10),
            'total_amount'       => $this->integer(),
            'invoice_payload'    => $this->string(255)->notNull(),
            'shipping_option_id' => $this->string(255)->notNull(),
            'order_info'         => $this->text(),
            'created_at'         => $this->timestamp()->defaultValue(null),
        ]);
        $this->addForeignKey('fk_user_id_pre_checkout_query', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_id_pre_checkout_query', $this->table);
        $this->dropTable($this->table);
    }
}
