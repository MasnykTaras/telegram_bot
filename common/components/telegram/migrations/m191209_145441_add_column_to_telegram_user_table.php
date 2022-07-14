<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram_user}}`.
 */
class m191209_145441_add_column_to_telegram_user_table extends Migration
{

    public $table = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'discount', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'discount');
    }
}
