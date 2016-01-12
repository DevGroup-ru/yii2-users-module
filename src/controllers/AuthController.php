<?php

namespace DevGroup\Users\controllers;

use DevGroup\Frontend\controllers\FrontendController;
use DevGroup\Users\actions\Login;
use DevGroup\Users\actions\Logout;
use DevGroup\Users\actions\Social;
use DevGroup\Users\actions\Registration;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AuthController extends FrontendController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'registration' => [
                'class' => Registration::className(),
            ],
            'social' => [
                'class' => Social::className(),
            ],
            'login' => [
                'class' => Login::className(),
            ],
            'logout' => [
                'class' => Logout::className(),
            ],
        ];
    }
}
