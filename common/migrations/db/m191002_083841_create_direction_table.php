<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%direction}}`.
 */
class m191002_083841_create_direction_table extends Migration
{

    public $table = '{{%direction}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'               => $this->primaryKey(),
            'sell_currency_id' => $this->integer(11)->notNull(),
            'buy_currency_id'  => $this->integer(11)->notNull(),
            'rate'             => $this->decimal(16, 8)->notNull(),
            'main_currency'    => $this->integer(11)->notNull(),
            'status'           => $this->tinyInteger(1)->notNull(),
            'min_sell'         => $this->decimal(16, 8)->notNull(),
            'min_buy'          => $this->decimal(16, 8)->notNull(),
            'max_sell'         => $this->decimal(16, 8)->notNull(),
            'max_buy'          => $this->decimal(16, 8)->notNull(),
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
