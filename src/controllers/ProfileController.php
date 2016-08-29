<?php

namespace DevGroup\Users\controllers;

use DevGroup\Frontend\controllers\FrontendController;
use DevGroup\Users\actions\ChangePassword;
use DevGroup\Users\actions\DeleteSocial;
use DevGroup\Users\actions\ManageSocial;
use DevGroup\Users\actions\Profile;
use DevGroup\Users\actions\Social;
use Yii;
use yii\filters\AccessControl;

/**
 * Class ProfileController
 *
 * @package DevGroup\Users\controllers
 */
class ProfileController extends FrontendController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => Profile::class,
            ],
            'change-password' => [
                'class' => ChangePassword::class,
            ],
            'manage-social' => [
                'class' => ManageSocial::class
            ],
            'add-social' => [
                'class' => Social::class
            ],
            'delete-social' => [
                'class' => DeleteSocial::class
            ]
        ];
    }
}
