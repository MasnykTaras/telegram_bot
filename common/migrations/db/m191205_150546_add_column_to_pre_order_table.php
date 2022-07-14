<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pre_order}}`.
 */
class m191205_150546_add_column_to_pre_order_table extends Migration
{

    public $table = '{{%pre_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'conversation_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'conversation_id');
    }
}
