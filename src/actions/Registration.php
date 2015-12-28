<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\FormCombinedAction;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\RegistrationForm;
use DevGroup\Users\models\User;
use DevGroup\Users\UsersModule;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

class Registration extends FormCombinedAction
{
    /** @var RegistrationForm */
    public $model = null;

    public $viewFile = null;

    public $formOptions = [
        'options' => [
            'class' => 'registration-form',
        ],
        'layout' => 'horizontal',
    ];

    public function init()
    {
        parent::init();
        if ($this->viewFile === null) {
            $this->viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/registration-form';
        }
    }

    public function getFooter()
    {
        return '';
    }

    public function defineParts()
    {
        return [
            'saveData' => [
                'function' => 'saveData',
            ],
            'renderForm' => [
                'function' => 'renderForm',
                'type' => 'plain',
            ],
        ];
    }

    public function beforeActionRun()
    {
        parent::beforeActionRun();
        $this->model = Yii::createObject(ModelMapHelper::RegistrationForm());
    }

    public function saveData()
    {
        if (Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            // perform AJAX validation
            return ActiveForm::validate($this->model);
        }

        if ($this->model->load(Yii::$app->request->post())) {
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

    public function renderForm()
    {
        return $this->render(
            $this->viewFile,
            [
                'model' => $this->model,
                'form' => $this->form,
            ]
        );
    }

    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Registration'),
            ]
        ];
    }

    public function title()
    {
        return Yii::t('users', 'Registration');
    }
}
