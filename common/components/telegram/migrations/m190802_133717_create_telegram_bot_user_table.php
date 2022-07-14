<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_user}}`.
 */
class m190802_133717_create_telegram_bot_user_table extends Migration
{

    public $table = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'            => $this->primaryKey(),
            'is_bot'        => $this->tinyInteger(2),
            'first_name'    => $this->string(255)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'),
            'last_name'     => $this->string(255)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'),
            'username'      => $this->string(255),
            'language_code' => $this->string(10),
            'created_at'    => $this->timestamp()->defaultValue(null),
            'updated_at'    => $this->timestamp()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
