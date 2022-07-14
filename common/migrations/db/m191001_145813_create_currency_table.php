<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m191001_145813_create_currency_table extends Migration
{
    public $table = "{{%currency}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                       => $this->primaryKey(),
            'name'                     => $this->string(255)->notNull(),
            'reserve'                  => $this->decimal(16, 8),
            'code'                     => $this->string(255),
            'buy_commission'           => $this->decimal(16, 8),
            'sell_commission'          => $this->decimal(16, 8),
            'apply_commission_on_sell' => $this->smallInteger(6),
            'apply_commission_on_buy'  => $this->smallInteger(6),
            'status'                   => $this->smallInteger(6)->notNull(),
            'symbol'                   => $this->string(255),
            'placeholder'              => $this->string(255),
            'regular'                  => $this->string(255),
            'iso_code'                 => $this->string(255),
            'card_number'              => $this->string(255),
            'parent_id'                => $this->integer(),
            'sell_fields'              => $this->json(),
            'buy_fields'               => $this->json(),
            'type'                     => $this->tinyInteger(1),
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
