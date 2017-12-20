<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_apartment`.
 */
class m171214_025901_create_user_apartment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_apartment', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'apartment_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('fkUser_user_apartment', 'user_apartment', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fkApartment_user_apartment', 'user_apartment', 'apartment_id', 'apartment', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idxUserApartment', 'user_apartment', ['user_id', 'apartment_id'], true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_apartment');
    }
}
