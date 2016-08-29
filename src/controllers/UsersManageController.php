<?php

namespace DevGroup\Users\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\Users\actions\BackendEditUser;
use DevGroup\Users\actions\BackendDeleteAction;
use DevGroup\Users\actions\BackendListUsers;
use DevGroup\Users\helpers\ModelMapHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class ManageController
 *
 * @package DevGroup\Users\controllers
 */
class UsersManageController extends BaseController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['users-user-view'],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            'users-user-edit',
                            'backend-detail-view'
                        ],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            'users-user-delete',
                        ],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['*'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => BackendListUsers::class
            ],
            'edit' => [
                'class' => BackendEditUser::class
            ],
            'delete' => [
                'class' => BackendDeleteAction::class,
                'modelClass' => isset(ModelMapHelper::User()['class']) ? ModelMapHelper::User()['class'] : '',
            ]
        ];
    }
}
