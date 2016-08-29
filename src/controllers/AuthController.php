<?php

namespace DevGroup\Users\controllers;

use DevGroup\Frontend\controllers\FrontendController;
use DevGroup\Users\actions\Login;
use DevGroup\Users\actions\Logout;
use DevGroup\Users\actions\RequestResetPassword;
use DevGroup\Users\actions\ResetPassword;
use DevGroup\Users\actions\Social;
use DevGroup\Users\actions\Registration;
use Yii;
use yii\filters\AccessControl;

class AuthController extends FrontendController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'registration' => [
                'class' => Registration::class,
            ],
            'social' => [
                'class' => Social::class,
            ],
            'login' => [
                'class' => Login::class,
            ],
            'logout' => [
                'class' => Logout::class,
            ],
            'request-reset-password' => [
                'class' => RequestResetPassword::class
            ],
            'reset-password' => [
                'class' => ResetPassword::class
            ]
        ];
    }
}
