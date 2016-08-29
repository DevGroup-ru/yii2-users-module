<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\RegistrationForm;
use DevGroup\Users\models\User;
use DevGroup\Users\UsersModule;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

/**
 * Class Registration
 *
 * @package DevGroup\Users\actions
 */
class Registration extends BaseAction
{
    /** @var RegistrationForm */
    public $model = null;

    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/registration-form';

    public $formOptions = [
        'options' => [
            'class' => 'registration-form m-form',
        ],
    ];


    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        $this->model = Yii::createObject(ModelMapHelper::RegistrationForm());
        return parent::beforeRun();
    }

    /**
     * @return string|Response
     * @throws \yii\base\ExitException
     */
    public function saveData()
    {
        if ($this->model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                // perform AJAX validation
                echo ActiveForm::validate($this->model);
                Yii::$app->end();
                return '';
            }

            /** @var User|bool $registeredUser */
            $registeredUser = $this->model->register();
            if ($registeredUser !== false) {
                $module = UsersModule::module();
                // login registered user if there's no need in confirmation
                $shouldLogin = $module->allowLoginInactiveAccounts || $module->emailConfirmationNeeded === false;
                if ($module->emailConfirmationNeeded === true && $registeredUser->is_active) {
                    $shouldLogin = true;
                }
                if ($shouldLogin && $registeredUser->login(UsersModule::module()->loginDuration)) {
                    $returnUrl = Yii::$app->request->get('returnUrl');
                    if ($returnUrl !== null) {
                        return $this->controller->redirect($returnUrl);
                    }
                }
                return $this->controller->goBack();
            }
        }
        return '';
    }

    /**
     * @return string|Response
     */
    public function run()
    {
        $saveResponse = $this->saveData();
        if ($saveResponse instanceof Response) {
            return $saveResponse;
        }
        return $this->controller->render(
            $this->viewFile,
            [
                'model' => $this->model,
                'formOptions' => $this->formOptions,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Registration'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function title()
    {
        return Yii::t('users', 'Registration');
    }
}
