<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\LoginForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

/**
 * Class Login
 *
 * @package DevGroup\Users\actions
 */
class Login extends BaseAction
{
    /**
     * @var string
     */
    public $viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/login';

    public $formOptions = [
        'options' => [
            'class' => 'login-form m-form',
        ],
    ];

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Login to site'),
            ]
        ];
    }

    /** @inheritdoc */
    public function run()
    {
        /** @var LoginForm $model */
        $model = Yii::createObject(ModelMapHelper::LoginForm());
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                // perform AJAX validation
                echo ActiveForm::validate($model);
                Yii::$app->end();
                return '';
            }
            if ($model->login()) {
                $returnUrl = Yii::$app->getUser()->getReturnUrl();
                if (true === (bool)$model->user->password_is_temporary) {
                    return $this->controller->redirect(
                        ['/users/profile/change-password', 'returnUrl' => $returnUrl]
                    );
                }
                return $this->controller->redirect($returnUrl);
            }
        }
        return $this->controller->render(
            $this->viewFile,
            [
                'model' => $model,
                'formOptions' => $this->formOptions,
            ]
        );
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Login to site');
    }
}
