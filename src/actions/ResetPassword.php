<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\User;
use Yii;
use yii\web\ServerErrorHttpException;

class ResetPassword extends BaseAction
{
    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/reset-password';

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Reset Password'),
            ]
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Reset Password');
    }


    public function run($token)
    {
        /** @var User $model */
        $model = User::findByPasswordResetToken($token);
        if ($model === null) {
            throw new ServerErrorHttpException("No user identity found");
        }
        $model->setScenario(User::SCENARIO_PASSWORD_RESET);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->removePasswordResetToken();
            if ($model->changePassword()) {
                Yii::$app->session->setFlash('info', Yii::t('users', 'Your password has been changed'));
                return $this->controller->redirect(['@login']);
            }
        }

        return $this->controller->render($this->viewFile, ['model' => $model]);

    }
}