<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders_creating_statistic}}`.
 */
class m191224_130012_create_orders_creating_statistic_table extends Migration
{

    public $table = '{{%orders_creating_statistic}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                 => $this->primaryKey(),
            'type'               => $this->tinyInteger(),
            'start'              => $this->integer(),
            'completed'          => $this->integer(),
            'email'              => $this->integer(),
            'email_verify_start' => $this->integer(),
            'email_verify_end'   => $this->integer(),
            'card'               => $this->integer(),
            'card_verify_start'  => $this->integer(),
            'card_verify_end'    => $this->integer(),
            'canceled_preorder'  => $this->integer(),
            'canceled_continued' => $this->integer(),
        ]);
        $this->insert($this->table, [
            'type'               => 1,
            'start'              => 0,
            'completed'          => 0,
            'email'              => 0,
            'email_verify_start' => 0,
            'email_verify_end'   => 0,
            'card'               => 0,
            'card_verify_start'  => 0,
            'card_verify_end'    => 0,
            'canceled_preorder'  => 0,
            'canceled_continued' => 0,
        ]);
        $this->insert($this->table, [
            'type'               => 2,
            'start'              => 0,
            'completed'          => 0,
            'email'              => 0,
            'email_verify_start' => 0,
            'email_verify_end'   => 0,
            'card'               => 0,
            'card_verify_start'  => 0,
            'card_verify_end'    => 0,
            'canceled_preorder'  => 0,
            'canceled_continued' => 0,
        ]);
        $this->insert($this->table, [
            'type'               => 3,
            'start'              => 0,
            'completed'          => 0,
            'email'              => 0,
            'email_verify_start' => 0,
            'email_verify_end'   => 0,
            'card'               => 0,
            'card_verify_start'  => 0,
            'card_verify_end'    => 0,
            'canceled_preorder'  => 0,
            'canceled_continued' => 0,
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
