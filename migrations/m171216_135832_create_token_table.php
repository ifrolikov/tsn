<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m171216_135832_create_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('token', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'data' => $this->text()->notNull()
        ]);
        $this->createIndex('idxToken', 'token', 'token', true);
        $this->addForeignKey('fkUser_token', 'token', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('token');
    }
}
