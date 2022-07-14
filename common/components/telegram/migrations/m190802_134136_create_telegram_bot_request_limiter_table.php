<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_request_limiter}}`.
 */
class m190802_134136_create_telegram_bot_request_limiter_table extends Migration
{

    public $table = '{{%telegram_bot_request_limiter}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                => $this->primaryKey(),
            'chat_id'           => $this->string(255),
            'inline_message_id' => $this->string(255),
            'method'            => $this->string(255),
            'created_at'        => $this->timestamp()->defaultValue(null),
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
