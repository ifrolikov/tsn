<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171214_025416_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull()->comment('Имя'),
            'second_name' => $this->string()->comment('Фамилия'),
            'father_name' => $this->string()->comment('Отчество'),
            'email' => $this->string()->notNull()->comment('E-mail'),
            'phone' => $this->string()->comment('Телефон'),
            'password' => $this->string()->notNull()->comment('Пароль'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
