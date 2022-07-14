<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%card}}`.
 */
class m191111_150912_add_column_to_card_table extends Migration
{

    public $table = '{{%card}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'code', $this->string(35));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'code');
    }
}
