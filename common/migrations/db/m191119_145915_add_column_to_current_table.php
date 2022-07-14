<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%current}}`.
 */
class m191119_145915_add_column_to_current_table extends Migration
{

    public $table = '{{%currency}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'email_validation', $this->tinyInteger(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'email_validation');
    }
}
