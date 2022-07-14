<?php

use yii\db\Migration;

/**
 * Class m191112_141229_add_column_to_order_column
 */
class m191112_141229_add_column_to_order_column extends Migration
{

    public $table = '{{%order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'recalculated', $this->string(35));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropColumn($this->table, 'recalculated');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191112_141229_add_column_to_order_column cannot be reverted.\n";

        return false;
    }
    */
}
