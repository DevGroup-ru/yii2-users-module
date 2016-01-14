<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\User;
use DevGroup\Users\models\UserService;
use yii\base\Action;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class DeleteSocial extends Action
{


    public function run($service_id)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new ServerErrorHttpException("No user identity found");
        }
        $userService = UserService::findOne(['social_service_id' => $service_id, 'user_id' => $user->id]);

        if($userService === null) {
            throw new NotFoundHttpException();
        }

        if($userService->delete())
        {
            Yii::$app->session->addFlash('success', Yii::t('users', 'Service has been deleted'));
        } else {
            Yii::$app->session->addFlash('error', Yii::t('users', 'Service has not been deleted'));
        }
        $this->controller->redirect(['@manage-social']);
    }


}