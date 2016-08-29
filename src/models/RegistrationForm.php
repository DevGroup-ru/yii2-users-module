<?php

namespace DevGroup\Users\models;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\UsersModule;
use Yii;
use yii\authclient\BaseClient;
use yii\base\DynamicModel;
use yii\db\Query;

class RegistrationForm extends DynamicModel
{
    /** @var \DevGroup\Users\models\User user instance if found */
    public $user = null;

    /**
     * RegistrationForm constructor.
     *
     * @param array $config Object config
     */
    public function __construct($config = [])
    {
        $attributes = UsersModule::module()->authorizationScenario()->attributes();
        parent::__construct($attributes, $config);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = UsersModule::module()->authorizationScenario()->attributeLabels();


        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = UsersModule::module()->authorizationScenario()->registrationScenario($this);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'registration-form';
    }

    /**
     * Performs registration of user.
     * Returns User object on success or false on failure.
     *
     * @return bool|User
     * @throws \yii\base\InvalidConfigException
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::createObject(ModelMapHelper::User());

        $user->setAttributes($this->attributes);

        return $user->register();
    }

    public function socialRegister(BaseClient &$client)
    {
        UsersModule::module()->authorizationScenario()->socialRegistrationScenario($this, $client);
        if (!$this->validate()) {
            return false;
        }
        /** @var User $user */
        $user = Yii::createObject(ModelMapHelper::User());

        $user->setAttributes($this->attributes);
        $user->is_active = true;

        return $user->register();
    }

    public function generateUsername($additionalData = [])
    {
        // try to use name part of email
        if (!empty($this->email)) {
            $this->username = explode('@', $this->email)[0];
            if ($this->validate(['username'])) {
                return $this->username;
            }
        }

        $additionalDataChecks = [
            'name',
            'full_name',
            'first_name',
            'last_name',
        ];
        foreach ($additionalDataChecks as $key) {
            if (isset($additionalData[$key])) {
                $this->username = $additionalData[$key];
                if ($this->validate(['username'])) {
                    return $this->username;
                }
            }
        }

        /** @var User $userModel */
        $userModel = Yii::createObject(ModelMapHelper::User());
        $tableName = $userModel->tableName();
        unset($userModel);

        while (!$this->validate(['username'])) {
            $row = (new Query())
                ->from($tableName)
                ->select('MAX(id) as id')
                ->one();
            $this->username = 'user' . ++$row['id'];
        }
        return $this->username;
    }
}
