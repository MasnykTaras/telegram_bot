<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%widget_text}}`.
 */
class m191105_155317_add_column_to_widget_text_table extends Migration
{

    public $table = '{{%widget_text}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'language', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'language');
    }
}
