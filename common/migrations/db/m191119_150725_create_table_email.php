<?php

use yii\db\Migration;

/**
 * Class m191119_150725_create_table_email
 */
class m191119_150725_create_table_email extends Migration
{

    public $table = '{{%email}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'email'      => $this->string(255)->notNull()->unique(),
            'status'     => $this->tinyInteger(1),
            'created_at' => $this->string(),
            'updated_at' => $this->string(),
            'user_id'    => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191119_150725_create_table_email cannot be reverted.\n";

        return false;
    }
    */
}
