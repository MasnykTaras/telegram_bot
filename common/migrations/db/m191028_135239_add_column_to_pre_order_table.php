<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pre_order}}`.
 */
class m191028_135239_add_column_to_pre_order_table extends Migration
{

    public $table = '{{%pre_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'created_at', $this->string(255));
        $this->addColumn($this->table, 'updated_at', $this->string(255));
        $this->addColumn($this->table, 'rate', $this->decimal(16, 8));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'created_at');
        $this->dropColumn($this->table, 'updated_at');
        $this->dropColumn($this->table, 'rate');
    }
}
