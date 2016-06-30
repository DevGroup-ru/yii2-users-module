<?php

namespace DevGroup\Users\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\Users\actions\DeleteAction;
use DevGroup\Users\actions\ListUsers;
use DevGroup\Users\actions\EditUser;
use DevGroup\Users\helpers\ModelMapHelper;

/**
 * Class ManageController
 *
 * @package DevGroup\Users\controllers
 */
class ManageController extends BaseController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ListUsers::class
            ],
            'edit' => [
                'class' => EditUser::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => isset(ModelMapHelper::User()['class']) ? ModelMapHelper::User()['class'] : '',
            ]
        ];
    }
}