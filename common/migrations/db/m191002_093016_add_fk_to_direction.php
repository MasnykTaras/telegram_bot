<?php

use yii\db\Migration;

/**
 * Class m191002_093016_add_fk_to_direction
 */
class m191002_093016_add_fk_to_direction extends Migration
{

    public $table    = '{{%direction}}';
    public $refTable = '{{%currency}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_direction_sell_currency_id_to_id', $this->table, 'sell_currency_id', $this->refTable, 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_direction_buy_currency_id_to_id', $this->table, 'buy_currency_id', $this->refTable, 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_direction_main_currency_id_to_id', $this->table, 'main_currency', $this->refTable, 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_direction_sell_currency_id_to_id', $this->table);
        $this->dropForeignKey('fk_direction_buy_currency_id_to_id', $this->table);
        $this->dropForeignKey('fk_direction_main_currency_id_to_id', $this->table);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191002_093016_add_fk_to_direction cannot be reverted.\n";

        return false;
    }
    */
}
