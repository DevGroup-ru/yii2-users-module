<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\models\ChangePasswordForm;
use Yii;

class ChangePassword extends BaseAction
{

    public $profileWidgetOptions = [];
    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/changePassword';


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

    public function run()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new ServerErrorHttpException("No user identity found");
        }

        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->changePassword()) {
                Yii::$app->session->setFlash('info', Yii::t('users', 'Your password has been changed'));
                return $this->controller->redirect(Yii::$app->request->url);
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