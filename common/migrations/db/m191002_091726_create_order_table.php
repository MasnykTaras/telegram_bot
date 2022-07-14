<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m191002_091726_create_order_table extends Migration
{

    public $table = '{{%order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'               => $this->primaryKey(),
            'hash'             => $this->string(255)->notNull(),
            'public_hash'      => $this->string(255)->notNull(),
            'sell_amount'      => $this->decimal(16, 8)->notNull(),
            'buy_amount'       => $this->decimal(16, 8)->notNull(),
            'sell_source'      => $this->string(255),
            'buy_target'       => $this->string(255),
            'payment_address'  => $this->string(255),
            'rate'             => $this->decimal(16, 8)->notNull(),
            'old_rate'         => $this->decimal(16, 8)->notNull(),
            'created_at'       => $this->string(255),
            'updated_at'       => $this->string(255),
            'sell_currency_id' => $this->integer(11)->notNull(),
            'buy_currency_id'  => $this->integer(11)->notNull(),
            'status'           => $this->tinyInteger(1)->notNull(),
            'sub_status'       => $this->tinyInteger(1),
            'main_email'       => $this->string(),
            'user_id'          => $this->integer(),
            'user_ip'          => $this->string(),
            'direction_id'     => $this->integer(11),
            'init_sell_amount' => $this->decimal(16, 8)->notNull(),
            'init_buy_amount'  => $this->decimal(16, 8)->notNull(),
            'our_buy_amount'   => $this->decimal(16, 8)->notNull(),
            'our_sell_amount'  => $this->decimal(16, 8)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
    }
}
