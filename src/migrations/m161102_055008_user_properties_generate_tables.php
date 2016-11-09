<?php

use yii\db\Migration;
use DevGroup\DataStructure\helpers\PropertiesTableGenerator;
use DevGroup\Users\models\User;

class m161102_055008_user_properties_generate_tables extends Migration
{
    public function up()
    {
        $table_generator = PropertiesTableGenerator::getInstance();
        $table_generator->generate("DevGroup\\Users\\models\\User");

        return true;
    }

    public function down()
    {
        $table_generator = PropertiesTableGenerator::getInstance();
        $table_generator->drop("DevGroup\\Users\\models\\User");

        return true;
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
