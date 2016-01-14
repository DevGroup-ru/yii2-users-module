<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\User;
use Yii;
use yii\web\ServerErrorHttpException;

class ManageSocial extends BaseAction
{
    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/manage-social';

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Manage Social'),
            ]
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Manage Social');
    }

    public function run()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new ServerErrorHttpException("No user identity found");
        }

        $services = array_reduce(
            $user->services,
            function ($arr, $i) {
                $arr[$i->socialService->class_name] =  $i;
                return $arr;
            },
            []
        );

        return $this->controller->render($this->viewFile, ['services' => $services]);
    }
}