<?php

namespace DevGroup\Users\controllers;

use DevGroup\Users\actions\Login;
use DevGroup\Users\actions\Social;
use DevGroup\Users\actions\Registration;
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
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
        ];
    }
}
