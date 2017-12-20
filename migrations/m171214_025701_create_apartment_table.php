<?php

use yii\db\Migration;

/**
 * Handles the creation of table `apartment`.
 */
class m171214_025701_create_apartment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('apartment', [
            'id' => $this->primaryKey(),
            'zip' => $this->integer()->notNull()->comment('Индекс'),
            'address' => $this->string()->notNull()->comment('Адрес'),
            'bill' => $this->string()->notNull()->comment('Номер агента (владельца счета)'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('apartment');
    }
}
