<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%preorder}}`.
 */
class m200124_105557_add_user_ip_column_to_preorder_table extends Migration
{

    public $table = '{{%pre_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'user_ip', $this->string(24));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'user_ip');
    }
}
