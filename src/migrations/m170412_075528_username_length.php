<?php

use yii\db\Migration;

class m170412_075528_username_length extends Migration
{
    public function safeUp()
    {
        $this->alterColumn(\DevGroup\Users\models\User::tableName(), '[[username]]', 'varchar(255) not null');
        return true;
    }

    public function safeDown()
    {
        $this->alterColumn(\DevGroup\Users\models\User::tableName(), '[[username]]', 'varchar(18) not null');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170412_075528_username_length cannot be reverted.\n";

        return false;
    }
    */
}
