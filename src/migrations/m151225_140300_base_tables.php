<?php

use yii\db\Migration;

class m151225_140300_base_tables extends Migration
{
    public function up()
    {
        mb_internal_encoding("UTF-8");
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable(
            '{{%user}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string(18)->notNull()->defaultValue(''),
                'username_is_temporary' => $this->boolean()->notNull()->defaultValue(0),
                'auth_key' => $this->string(32)->notNull()->defaultValue(''),
                'password_hash' => $this->string()->notNull()->defaultValue(''),
                'password_reset_token' => $this->string()->notNull()->defaultValue(''),
                'email' => $this->string()->notNull()->defaultValue(''),
                'email_activation_token' => $this->string()->notNull()->defaultValue(''),
                'is_active' => $this->boolean()->notNull()->defaultValue(0),
                'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
                'updated_at' => $this->dateTime(),
                'activated_at' => $this->dateTime(),
                'last_login_at' => $this->dateTime(),
                'phone' => $this->string(),
            ],
            $tableOptions
        );

        $this->createIndex('byUser', '{{%user}}', ['username'], true);
        $this->createIndex('byMail', '{{%user}}', ['email'], true);

        $this->createTable(
            '{{%user_service}}',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'social_service_id' => $this->integer()->notNull(),
                'service_id' => $this->integer()->notNull(),
            ],
            $tableOptions
        );
        $this->addForeignKey(
            'usersrv',
            '{{%user_service}}',
            [
                'user_id'
            ],
            '{{%user}}',
            [
                'id'
            ],
            'CASCADE'
        );

        $this->createTable(
            '{{%social_service}}',
            [
                'id' => $this->primaryKey(),
                'bem_modifier' => $this->string()->notNull()->defaultValue(''),
                'class_name' => $this->string()->notNull(),
                'packed_json_params' => $this->text(),
            ],
            $tableOptions
        );

        $this->createTable(
            '{{%social_service_translation}}',
            [
                'model_id' => $this->integer()->notNull(),
                'language_id' => $this->integer()->notNull(),
                'name' => $this->string(),
            ],
            $tableOptions
        );
        $this->addPrimaryKey('pkSSt', '{{%social_service_translation}}', ['model_id', 'language_id']);
        $this->addForeignKey(
            'soctransl',
            '{{%social_service_translation}}',
            [
                'model_id'
            ],
            '{{%social_service}}',
            [
                'id'
            ],
            'CASCADE'
        );
        $this->addForeignKey(
            'usersvcbind',
            '{{%user_service}}',
            [
                'social_service_id'
            ],
            '{{%social_service}}',
            [
                'id'
            ],
            'CASCADE'
        );

        $this->createTable(
            '{{%social_mappings}}',
            [
                'id' => $this->primaryKey(),
                'social_service_id' => $this->integer()->notNull(),
                'model_attribute' => $this->string()->notNull(),
                'social_attributes' => $this->string()->notNull(),
            ],
            $tableOptions
        );
        $this->addForeignKey(
            'socmap',
            '{{%social_mappings}}',
            [
                'social_service_id'
            ],
            '{{%social_service}}',
            [
                'id'
            ],
            'CASCADE'
        );

        /// test data
        $this->insert(
            '{{%social_service}}',
            [
                'bem_modifier' => 'facebook',
                'class_name' => 'DevGroup\Users\social\Facebook',
                'packed_json_params' => \yii\helpers\Json::encode([
                    'clientId' => '1545062422479434',
                    'clientSecret' => 'c71aafc0e6614d6ac8011100249dd58d',
                ]),
            ]
        );

        $this->insert(
            '{{%social_service_translation}}',
            [
                'language_id' => 1,
                'model_id' => 1,
                'name' => 'Facebook',
            ]
        );
        $this->insert(
            '{{%social_service_translation}}',
            [
                'language_id' => 2,
                'model_id' => 1,
                'name' => 'Мордокнига',
            ]
        );
    }

    public function down()
    {

        $this->dropTable('{{%social_mappings}}');
        $this->dropTable('{{%social_service_translation}}');
        $this->dropTable('{{%user_service}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%social_service}}');


    }
}
