<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_poll}}`.
 */
class m190802_134057_create_telegram_bot_poll_table extends Migration
{

    public $table = '{{%telegram_bot_poll}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'question'   => $this->string(255)->notNull(),
            'options'    => $this->text()->notNull(),
            'is_closed'  => $this->tinyInteger(2)->defaultValue(0),
            'created_at' => $this->timestamp()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
