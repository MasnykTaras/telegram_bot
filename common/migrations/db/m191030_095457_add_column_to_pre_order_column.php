<?php

use yii\db\Migration;

/**
 * Class m191030_095457_add_column_to_pre_order_column
 */
class m191030_095457_add_column_to_pre_order_column extends Migration
{

    public $table = '{{%pre_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn($this->table, 'chat_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'chat_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191030_095457_add_column_to_pre_order_column cannot be reverted.\n";

        return false;
    }
    */
}
