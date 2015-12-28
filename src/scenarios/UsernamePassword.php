<?php

namespace DevGroup\Users\scenarios;

use DevGroup\Users\models\RegistrationForm;
use DevGroup\Users\models\User;
use DevGroup\Users\models\LoginForm;
use Yii;

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
            'inactiveUsers' => $this->inactiveUsers($loginForm),
            'validatePassword' => $this->validatePassword($loginForm),
        ];

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
                ],
                'required',
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
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'Username'),
            'password' => Yii::t('users', 'Password'),
            'email' => Yii::t('users', 'E-Mail'),
        ];
    }

    public function registrationFormPartialView ()
    {
        return '@vendor/devgroup/yii2-users-module/src/scenarios/views/username-password-registration';
    }

    public function loginFormPartialView ()
    {
        return '@vendor/devgroup/yii2-users-module/src/scenarios/views/username-password-login';
    }
}
