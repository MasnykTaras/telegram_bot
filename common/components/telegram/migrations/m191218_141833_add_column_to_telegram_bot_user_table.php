<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram_bot_user}}`.
 */
class m191218_141833_add_column_to_telegram_bot_user_table extends Migration
{
    public $table = '{{%telegram_bot_user}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'spam_allowed', $this->tinyInteger(10)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'spam_allowed');
    }
}
