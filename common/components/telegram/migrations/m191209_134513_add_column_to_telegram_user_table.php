<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram_user}}`.
 */
class m191209_134513_add_column_to_telegram_user_table extends Migration
{

    public $table = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'is_connected', $this->tinyInteger(2));
        $this->addColumn($this->table, 'hash', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'hash');
        $this->dropColumn($this->table, 'is_connected');
    }
}
