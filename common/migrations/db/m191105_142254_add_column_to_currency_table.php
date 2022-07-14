<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%currency}}`.
 */
class m191105_142254_add_column_to_currency_table extends Migration
{

    public $table = '{{%currency}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'card_validation', $this->tinyInteger(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'card_validation');
    }
}
