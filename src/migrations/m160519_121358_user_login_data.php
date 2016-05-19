<?php

use DevGroup\Users\models\User;
use yii\db\Migration;

class m160519_121358_user_login_data extends Migration
{
    public function up()
    {
        $this->addColumn(
            User::tableName(),
            'packed_json_login_data',
            $this->text()
        );
    }

    public function down()
    {
        $this->dropColumn(
            User::tableName(),
            'packed_json_login_data'
        );
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
