<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%black_list}}`.
 */
class m191220_110517_create_black_list_table extends Migration
{

    public $table = '{{%black_list}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'          => $this->primaryKey(),
            'ip'          => $this->string(255),
            'currency_id' => $this->integer(),
            'email'       => $this->string(255),
            'card_number' => $this->string(255),
            'description' => $this->text(),
            'created_at'  => $this->string(255),
            'updated_at'  => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%black_list}}');
    }
}
