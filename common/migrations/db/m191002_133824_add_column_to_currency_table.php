<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%currency}}`.
 */
class m191002_133824_add_column_to_currency_table extends Migration
{

    public $table = '{{%currency}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'precision', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'precision');
    }
}
