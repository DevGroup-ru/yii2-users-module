<?php

namespace DevGroup\Users\controllers;

use DevGroup\Users\actions\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ProfileController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update'],
                'rules' => [
                    [
                        'actions' => ['update'],
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

        ];
    }
}
