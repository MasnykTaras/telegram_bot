<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_chosen_inline_result}}`.
 */
class m190802_133858_create_telegram_bot_chosen_inline_result_table extends Migration
{

    public $table     = '{{%telegram_bot_chosen_inline_result}}';
    public $userTable = '{{%telegram_bot_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table,
                [
                    'id'                => $this->primaryKey(),
                    'result_id'         => $this->string(255),
                    'user_id'           => $this->integer(),
                    'location'          => $this->string(255),
                    'inline_message_id' => $this->string(255),
                    'query'             => $this->text(),
                    'created_at'        => $this->timestamp()->defaultValue(null),
        ]);
        $this->addForeignKey('fk_user_id_chosen_inline_result', $this->table, 'user_id', $this->userTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_id_chosen_inline_result', $this->table);
        $this->dropTable($this->table);
    }
}
