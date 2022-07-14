<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%card}}`.
 */
class m191105_151256_create_card_table extends Migration
{

    public $table = "{{%card}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'          => $this->primaryKey(),
            'card_number' => $this->string(255)->notNull()->unique(),
            'status'      => $this->tinyInteger(1),
            'system'      => $this->tinyInteger(10),
            'created_at'  => $this->string(),
            'user_id'     => $this->integer(),
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
