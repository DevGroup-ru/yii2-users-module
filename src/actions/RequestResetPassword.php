<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\RequestResetPasswordForm;
use Yii;

/**
 * Class ResetPassword
 *
 * @package DevGroup\Users\actions
 */
class RequestResetPassword extends BaseAction
{
    /**
     * @var string
     */
    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/request-reset-password';

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Request to reset password'),
            ]
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Request to reset password');
    }

    /**
     * @return string
     */
    public function run()
    {
        $model = new RequestResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('info', Yii::t('users', 'Check your email for further instructions.'));
                return $this->controller->redirect(['@login']);
            }
        }
        return $this->controller->render($this->viewFile, ['model' => $model]);
    }
}
