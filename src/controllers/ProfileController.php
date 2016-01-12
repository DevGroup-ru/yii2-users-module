<?php

namespace DevGroup\Users\controllers;

use DevGroup\Frontend\controllers\FrontendController;
use DevGroup\Users\actions\ChangePassword;
use DevGroup\Users\actions\Profile;
use Yii;
use yii\filters\AccessControl;

class ProfileController extends FrontendController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'change-password'],
                'rules' => [
                    [
                        'actions' => ['update', 'change-password'],
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
            'update' => [
                'class' => Profile::className(),
            ],
            'change-password' => [
                'class' => ChangePassword::className(),
            ],
        ];
    }
}
