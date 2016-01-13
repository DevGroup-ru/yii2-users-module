<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\ResetPasswordForm;
use Yii;

/**
 * Class ResetPassword
 * @package DevGroup\Users\actions
 */
class ResetPassword extends BaseAction
{
    /**
     * @var string
     */
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

    /**
     * @return string
     */
    public function run()
    {
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('users', 'Check your email for further instructions.');
                return $this->controller->redirect(['@login']);
            }
        }

        return $this->controller->render($this->viewFile, ['model' => $model]);
    }

}