<?php

use yii\db\Migration;

class m160112_124301_clients extends Migration
{
    public function up()
    {
        mb_internal_encoding("UTF-8");
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;


        /// test data
        $this->insert(
            '{{%social_service}}',
            [
                'bem_modifier' => 'vk',
                'class_name' => 'DevGroup\Users\social\VKontakte',
                'packed_json_params' => \yii\helpers\Json::encode([

                ]),
            ]
        );
        $this->insert(
            '{{%social_service}}',
            [
                'bem_modifier' => 'google',
                'class_name' => 'DevGroup\Users\social\Google',
                'packed_json_params' => \yii\helpers\Json::encode([

                ]),
            ]
        );


        $this->insert(
            '{{%social_service_translation}}',
            [
                'language_id' => 1,
                'model_id' => 3,
                'name' => 'VK',
            ]
        );
        $this->insert(
            '{{%social_service_translation}}',
            [
                'language_id' => 2,
                'model_id' => 3,
                'name' => 'ВКонтакте',
            ]
        );
        $this->insert(
            '{{%social_service_translation}}',
            [
                'language_id' => 1,
                'model_id' => 4,
                'name' => 'GooglePlus',
            ]
        );
        $this->insert(
            '{{%social_service_translation}}',
            [
                'language_id' => 2,
                'model_id' => 4,
                'name' => 'Google+',
            ]
        );

    }

    public function down()
    {

        $this->delete('{{%social_service_translation}}', ['model_id'=>3]);
        $this->delete('{{%social_service_translation}}', ['model_id'=>4]);
        $this->delete('{{%social_service}}', ['id'=>3]);
        $this->delete('{{%social_service}}', ['id'=>4]);


    }
}
