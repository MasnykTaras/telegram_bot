<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%direction}}`.
 */
class m191004_090501_add_column_to_direction_table extends Migration
{

    public $table = '{{%direction}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'direction_fields', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'direction_fields');
    }

}
