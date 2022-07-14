<?php

use yii\db\Migration;

/**
 * Class m191002_093612_add_fk_to_order
 */
class m191002_093612_add_fk_to_order extends Migration
{
    public $order     = '{{%order}}';
    public $direction = '{{%direction}}';
    public $currency  = '{{%currency}}';
    public $user      = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_order_sell_currency_id_to_id', $this->order, 'sell_currency_id', $this->currency, 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_order_buy_currency_id_to_id', $this->order, 'buy_currency_id', $this->currency, 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_order_direction_id_to_id', $this->order, 'direction_id', $this->direction, 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_direction_id_to_id', $this->order);
        $this->dropForeignKey('fk_order_buy_currency_id_to_id', $this->order);
        $this->dropForeignKey('fk_order_sell_currency_id_to_id', $this->order);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191002_093612_add_fk_to_order cannot be reverted.\n";

        return false;
    }
    */
}
