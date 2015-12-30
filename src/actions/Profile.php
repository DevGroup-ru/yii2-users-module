<?php

namespace DevGroup\Users\actions;

use DevGroup\Frontend\RedirectHelper;
use DevGroup\Users\models\User;
use Yii;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\widgets\ActiveForm;

class Profile extends BaseAction
{
    public $profileWidgetOptions = [];
    public $viewFile = '';

    public function init()
    {
        parent::init();
        if (empty($this->viewFile)) {
            $this->viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/profile';
        }
    }

    public function run()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new ServerErrorHttpException("No user identity found");
        }

        $user->setScenario(User::SCENARIO_PROFILE_UPDATE);

        if ($user->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                // perform AJAX validation
                echo ActiveForm::validate($user);
                Yii::$app->end();
                return '';
            }

            if ($user->username_is_temporary && count($user->getDirtyAttributes(['username']))===1) {
                $user->username_is_temporary = false;
            }

            if ($user->save()) {
                $returnUrl = RedirectHelper::getPostedReturnUrl();
                if ($returnUrl !== null) {
                    return $this->controller->redirect($returnUrl);
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'Your profile sucessfully updated.'));
                }
            }
            

        }

        return $this->controller->render(
            $this->viewFile,
            [
                'profileWidgetOptions' => $this->profileWidgetOptions,
                'user' => $user,
            ]
        );
    }

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        [
            [
                'label' => Yii::t('users', 'Profile update'),
            ]
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Profile update');
    }
}
