<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%currency}}`.
 */
class m200124_142907_add_ip_available_column_to_currency_table extends Migration
{

    public $table = '{{%currency}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'ip_validation', $this->tinyInteger(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'ip_validation');
    }
}
