<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pre_order}}`.
 */
class m191104_141724_add_column_to_pre_order_table extends Migration
{

    public $table = '{{%pre_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'buy_phone', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'buy_phone');
    }
}
