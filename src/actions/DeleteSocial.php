<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\User;
use DevGroup\Users\models\UserService;
use yii\base\Action;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteSocial
 *
 * @package DevGroup\Users\actions
 */
class DeleteSocial extends Action
{

    /**
     * @param $service_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($service_id)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('users', 'No user identity found'));
        }
        $userService = UserService::findOne(['social_service_id' => $service_id, 'user_id' => $user->id]);
        if ($userService === null) {
            throw new NotFoundHttpException(Yii::t(
                'users',
                'Service with id \'{serviceId}\' not found!',
                ['serviceId' => $service_id]
            ));
        }
        if ($userService->delete()) {
            Yii::$app->session->addFlash('success', Yii::t('users', 'Service has been deleted'));
        } else {
            Yii::$app->session->addFlash('error', Yii::t('users', 'Service has not been deleted'));
        }
        return $this->controller->redirect(['@manage-social']);
    }
}
