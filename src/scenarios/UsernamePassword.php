<?php

namespace DevGroup\Users\scenarios;

use DevGroup\Users\helpers\PasswordHelper;
use DevGroup\Users\models\RegistrationForm;
use DevGroup\Users\models\User;
use DevGroup\Users\models\LoginForm;
use DevGroup\Users\UsersModule;
use Yii;
use yii\authclient\BaseClient;

class UsernamePassword extends BaseAuthorizationPair
{
    public $requireEmail = false;

    public function loginScenario(LoginForm &$loginForm)
    {
        $rules = [
            'usernameTrim' => [
                'username',
                'filter',
                'filter' => 'trim',
            ],
            'login' => [
                [
                    'username',
                    'password',
                ],
                'required',
            ],
            'findUsername' => $this->findUserByField($loginForm, 'username'),
        ];
        $inactiveRule = $this->inactiveUsers($loginForm);
        if (!empty($inactiveRule)) {
            $rules['inactiveUsers'] = $inactiveRule;
        }
        $rules['validatePassword'] = $this->validatePassword($loginForm);


        return $rules;
    }

    public function registrationScenario(RegistrationForm &$registrationForm)
    {
        $rules = [
            'trimUsername' => [
                [
                    'username',
                ],
                'filter',
                'filter' => 'trim',
            ],
            'requiredFields' => [
                [
                    'username',
                    'password',
                    'confirmPassword'
                ],
                'required',
            ],
            'confirmPassword' => [
                'confirmPassword',
                'compare',
                'compareAttribute' => 'password',
            ],
            'uniqueUsername' => [
                'username',
                'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'username',
                'message' => Yii::t('users', 'This username has already been taken'),
            ],
            'usernameLength' => [
                'username',
                'string',
                'min' => 3,
                'max' => 18,
            ],
        ];

        if ($this->requireEmail === true) {
            $rules['emailRequired'] = [
                'email',
                'required',
            ];
            $rules['emailUnique'] = [
                'email',
                'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'email',
                'message' => Yii::t('users', 'This email address has already been taken'),
            ];
        }

        $this->addEmailRules($rules);

        $this->addPasswordRules($rules);

        return $rules;
    }

    public function credentialsUpdateScenario()
    {
        return [

        ];
    }

    public function attributes()
    {
        return [
            'username',
            'password',
            'email',
            'username_is_temporary',
            'confirmPassword',
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'Username'),
            'password' => Yii::t('users', 'Password'),
            'email' => Yii::t('users', 'E-Mail'),
            'confirmPassword' => Yii::t('users', 'Confirm Password'),
        ];
    }

    public function registrationFormPartialView()
    {
        return '@vendor/devgroup/yii2-users-module/src/scenarios/views/username-password-registration';
    }

    public function loginFormPartialView()
    {
        return '@vendor/devgroup/yii2-users-module/src/scenarios/views/username-password-login';
    }

    public function socialRegistrationScenario(RegistrationForm &$registrationForm, BaseClient &$client)
    {
        if (empty($registrationForm->username)) {
            $registrationForm->generateUsername($client->getUserAttributes());
            $registrationForm->username_is_temporary = true;
        }

        if (empty($registrationForm->password)) {
            $registrationForm->password = PasswordHelper::generate(UsersModule::module()->generatedPasswordLength);
            $registrationForm->confirmPassword = $registrationForm->password;
        }

    }
}
