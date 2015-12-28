<?php

namespace DevGroup\Users\scenarios;

use DevGroup\TagDependencyHelper\LazyCache;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\helpers\PasswordHelper;
use DevGroup\Users\models\LoginForm;
use DevGroup\Users\models\RegistrationForm;
use DevGroup\Users\UsersModule;
use Yii;
use yii\base\Object;
use yii\caching\TagDependency;

abstract class BaseAuthorizationPair extends Object
{
    public $minPasswordLength = 6;
    public $emailCheckDNS = false;
    public $emailEnableIDN = false;

    abstract public function attributes();

    abstract public function attributeLabels();

    abstract public function loginScenario(LoginForm &$loginForm);

    abstract public function registrationScenario(RegistrationForm &$registrationForm);

    abstract public function credentialsUpdateScenario();

    abstract public function registrationFormPartialView();

    abstract public function loginFormPartialView();

    protected function addEmailRules(&$rules)
    {
        $rules['standardEmailRules'] = [
            [
                'email',
            ],
            'email',
            'checkDNS' => $this->emailCheckDNS,
            'enableIDN' => $this->emailEnableIDN,
        ];
        $rules['trimEmail'] = [
            [
                'email',
            ],
            'filter',
            'filter' => 'trim',
        ];
    }

    protected function addPasswordRules(&$rules)
    {
        $rules['passwordLength'] = [
            [
                'password',
            ],
            'string',
            'min' => $this->minPasswordLength,
        ];
    }

    /**
     * Finds user by specified field(username, email, phone)
     * @param \DevGroup\Users\models\LoginForm $loginForm
     * @param string                           $field
     *
     * @return array
     */
    protected function findUserByField(LoginForm &$loginForm, $field = 'username')
    {
        return [
            $field,
            function ($attribute) use (&$loginForm) {
                $user = ModelMapHelper::User();
                /** @var LazyCache $cache */
                $cache = Yii::$app->cache;
                $value = $loginForm->$attribute;
                $loginForm->user = $cache->lazy(function () use ($user, $value, $attribute) {
                    return $user::find()
                        ->where(
                            "$attribute=:param",
                            [
                                ':param' => $value,
                            ]
                        )->one();
                }, "User:by:$attribute:$value", 86400, new TagDependency(['tags'=>$user::commonTag()]));
            }
        ];
    }

    /**
     * Adds password validation rule for login scenario
     *
     * @param \DevGroup\Users\models\LoginForm $loginForm
     *
     * @return array
     */
    protected function validatePassword(LoginForm &$loginForm)
    {
        return [
            'password',
            function ($attribute) use (&$loginForm) {
                if ($loginForm->user === null ||
                    !PasswordHelper::validate($loginForm->password, $loginForm->user->password_hash)
                ) {
                    $loginForm->addError($attribute, Yii::t('users', 'Invalid login or password'));
                }
            }
        ];
    }

    /**
     * Adds validation rule to not accept inactive users if such feature is toggled on in module configuration.
     *
     * @param \DevGroup\Users\models\LoginForm $loginForm
     *
     * @return array
     */
    protected function inactiveUsers(LoginForm &$loginForm)
    {
        if (UsersModule::module()->allowLoginInactiveAccounts === false) {
            return [];
        }
        return [
            'username',
            function ($attribute) use (&$loginForm) {
                if ($loginForm->user !== null && $loginForm->user->is_active === false) {
                    $loginForm->addError($attribute, Yii::t('users', 'You need to confirm your email address'));
                }
            }
        ];
    }
}
