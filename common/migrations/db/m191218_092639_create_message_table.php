<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m191218_092639_create_message_table extends Migration
{
    public $table = '{{%message}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'message' => $this->string(255)->notNull(),
            'is_sent' => $this->tinyInteger(2),
            'language' => $this->string(10),
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
