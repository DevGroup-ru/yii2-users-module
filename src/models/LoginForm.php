<?php

namespace DevGroup\Users\models;

use DevGroup\Users\UsersModule;
use Yii;
use yii\base\DynamicModel;

/**
 * Class LoginForm
 *
 * @package DevGroup\Users\models
 * @property string $password
 */
class LoginForm extends DynamicModel
{
    /** @var string Whether to remember the user */
    public $rememberMe = true;

    /** @var \DevGroup\Users\models\User user instance if found */
    public $user = null;

    /**
     * LoginForm constructor.
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
        $labels['rememberMe'] = Yii::t('users', 'Remember me');
        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = UsersModule::module()->authorizationScenario()->loginScenario($this);
        $rules['rememberCast'] = [
            'rememberMe',
            'filter',
            'filter' => 'boolval',
        ];
        $rules['rememberMe'] = [
            'rememberMe',
            'boolean',
        ];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'login-form';
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $module = UsersModule::module();
        if ($this->validate()) {
            return $this->user->login($this->rememberMe ? $module->loginDuration : 0);
        } else {
            return false;
        }
    }
}
