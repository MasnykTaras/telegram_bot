<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_chat}}`.
 */
class m190802_133739_create_telegram_bot_chat_table extends Migration
{

    public $table = '{{%telegram_bot_chat}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table,
                [
                    'id'                             => $this->primaryKey(),
                    'title'                          => $this->string(255),
                    'username'                       => $this->string(255),
                    'first_name'                     => $this->string(255)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'),
                    'last_name'                      => $this->string(255)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'),
                    'all_members_are_administrators' => $this->tinyInteger(2),
                    'created_at'                     => $this->timestamp()->defaultValue(null),
                    'updated_at'                     => $this->timestamp()->defaultValue(null),
                    'old_id'                         => $this->integer(),
                ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'
        );
        $this->execute("ALTER TABLE " . $this->table . " ADD COLUMN `type` ENUM('private', 'group', 'supergroup', 'channel')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
