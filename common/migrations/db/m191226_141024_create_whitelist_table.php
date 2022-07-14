<?php

use yii\db\Migration;
use common\models\Whitelist;

/**
 * Handles the creation of table `{{%whitelist}}`.
 */
class m191226_141024_create_whitelist_table extends Migration
{

    public $table = '{{%whitelist}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'   => $this->primaryKey(),
            'ip'   => $this->string(128)->notNull(),
            'name' => $this->string(128),
            'type' => $this->integer()->defaultValue(Whitelist::TYPE_ADMIN)
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
