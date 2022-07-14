<?php

use yii\db\Migration;

/**
 * Class m191030_095626_add_column_to_order_column
 */
class m191030_095626_add_column_to_order_column extends Migration
{

    public $table = '{{%order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'pay_url', $this->string(1024));
        $this->addColumn($this->table, 'wallet_in', $this->string(255));
        $this->addColumn($this->table, 'chat_id', $this->string(255));
        $this->renameColumn($this->table, 'public_hash', 'order_hash');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn($this->table, 'order_hash', 'public_hash');
        $this->dropColumn($this->table, 'chat_id');
        $this->dropColumn($this->table, 'pay_url');
        $this->dropColumn($this->table, 'wallet_in');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191030_095626_add_column_to_order_column cannot be reverted.\n";

        return false;
    }
    */
}
