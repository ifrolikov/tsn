<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_role`.
 */
class m171216_153940_create_user_role_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_role', [
            'user_id' => $this->integer()->notNull(),
            'role' => $this->string()->notNull()
        ]);

        $this->addPrimaryKey('pkUserRole_user_role', 'user_role', ['user_id', 'role']);
        $this->addForeignKey('fkUser_user_role', 'user_role', 'user_id', 'user', 'id', 'cascade', 'cascade');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_role');
    }
}
