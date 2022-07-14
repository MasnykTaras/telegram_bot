<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram_bot_user}}`.
 */
class m200323_100116_add_language_column_to_telegram_bot_user_table extends Migration
{

    public $table = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'preferred_language', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'preferred_language');
    }
}
