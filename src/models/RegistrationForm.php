<?php

namespace DevGroup\Users\models;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\User;
use DevGroup\Users\UsersModule;
use Yii;
use yii\base\DynamicModel;

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
}
