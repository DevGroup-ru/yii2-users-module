<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\ChangePasswordForm;
use DevGroup\Users\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ChangePassword
 *
 * @package DevGroup\Users\actions
 */
class ChangePassword extends BaseAction
{

    public $profileWidgetOptions = [];
    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/change-password';


    /**
     * @return array
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Change Password'),
            ]
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Change Password');
    }

    /**
     * @param string $returnUrl
     * @return string|\yii\web\Response
     * @throws ServerErrorHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($returnUrl = '')
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('users', 'No user identity found'));
        }
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->changePassword()) {
                Yii::$app->session->setFlash('info', Yii::t('users', 'Your password has been changed'));
                if (true === empty($returnUrl)) {
                    return $this->controller->refresh();
                } else {
                    return $this->controller->redirect($returnUrl);
                }
            }
        }
        return $this->controller->render(
            $this->viewFile,
            [
                'model' => $model
            ]
        );
    }
}
