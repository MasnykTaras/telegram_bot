<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_bot_telegram_update}}`.
 */
class m190802_134113_create_telegram_bot_telegram_update_table extends Migration
{

    public $table        = '{{%telegram_bot_telegram_update}}';
    public $massageTable = '{{%telegram_bot_message}}';
    public $editedMessageTable      = '{{%telegram_bot_edited_message}}';
    public $inlineQueryTable        = '{{%telegram_bot_inline_query}}';
    public $chosenInlineResultTable = '{{%telegram_bot_chosen_inline_result}}';
    public $callbackQueryTable      = '{{%telegram_bot_callback_query}}';
    public $shippingQueryTable      = '{{%telegram_bot_shipping_query}}';
    public $preCheckoutQueryTable   = '{{%telegram_bot_pre_checkout_query}}';
    public $pollTable               = '{{%telegram_bot_poll}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'                      => $this->primaryKey(),
            'chat_id'                 => $this->integer(),
            'message_id'              => $this->integer(),
            'edited_message_id'       => $this->integer(),
            'edited_channel_post_id'  => $this->integer(),
            'channel_post_id'         => $this->integer(),
            'inline_query_id'         => $this->integer(),
            'chosen_inline_result_id' => $this->integer(),
            'callback_query_id'       => $this->integer(),
            'shipping_query_id'       => $this->integer(),
            'pre_checkout_query_id'   => $this->integer(),
            'poll_id'                 => $this->integer(),
        ]);
        $this->addForeignKey('fk_chat_id_and_message_id_telegram_update', $this->table, ['chat_id', 'message_id'], $this->massageTable, ['chat_id', 'id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_edited_message_id_telegram_update', $this->table, 'edited_message_id', $this->editedMessageTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chat_id_and_channel_post_id_telegram_update', $this->table, ['chat_id', 'channel_post_id'], $this->massageTable, ['chat_id', 'id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_edited_channel_post_id_telegram_update', $this->table, 'edited_channel_post_id', $this->editedMessageTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_inline_query_id_telegram_update', $this->table, 'inline_query_id', $this->inlineQueryTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_chosen_inline_result_id_telegram_update', $this->table, 'chosen_inline_result_id', $this->chosenInlineResultTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_callback_query_id_telegram_update', $this->table, 'callback_query_id', $this->callbackQueryTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_shipping_query_id_telegram_update', $this->table, 'shipping_query_id', $this->shippingQueryTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_pre_checkout_query_id_telegram_update', $this->table, 'pre_checkout_query_id', $this->preCheckoutQueryTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_poll_id_telegram_update', $this->table, 'poll_id', $this->pollTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_poll_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_pre_checkout_query_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_shipping_query_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_callback_query_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_chosen_inline_result_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_inline_query_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_edited_channel_post_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_chat_id_and_channel_post_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_edited_message_id_telegram_update', $this->table);
        $this->dropForeignKey('fk_chat_id_and_message_id_telegram_update', $this->table);
        $this->dropTable($this->table);
    }
}
