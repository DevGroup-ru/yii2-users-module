<?php

namespace DevGroup\Users\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\Users\actions\ManageUsers;
use DevGroup\Users\actions\EditUser;

class BackendController extends BaseController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ManageUsers::class
            ],
            'edit' => [
                'class' => EditUser::class
            ],
            'create' => [
                'class' => EditUser::class
            ]
        ];
    }
}