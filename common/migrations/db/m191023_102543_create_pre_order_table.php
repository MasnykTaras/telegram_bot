<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pre_order}}`.
 */
class m191023_102543_create_pre_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public $table = '{{%pre_order}}';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                   => $this->primaryKey(),
            'user_id'              => $this->integer(),
            'direction_id'         => $this->integer(),
            'main_email'           => $this->string(255),
            'sell_amount'          => $this->decimal(16, 8),
            'sell_wallet'          => $this->string(255),
            'sell_card_first_name' => $this->string(255),
            'sell_card_last_name'  => $this->string(255),
            'sell_phone'           => $this->string(255),
            'buy_wallet'           => $this->string(255),
            'buy_card_first_name'  => $this->string(255),
            'buy_card_middle_name' => $this->string(255),
            'buy_card_last_name'   => $this->string(255),
            'status'               => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
