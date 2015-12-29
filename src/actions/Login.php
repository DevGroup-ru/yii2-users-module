<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\LoginForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

class Login extends BaseAction
{
    /**
     * @var string
     */
    public $viewFile = '';

    public $formOptions = [
        'options' => [
            'class' => 'registration-form',
        ],
        'layout' => 'horizontal',
    ];

    public function init()
    {
        parent::init();
        if (empty($this->viewFile)) {
            $this->viewFile = '@vendor/devgroup/yii2-users-module/src/actions/views/login';
        }
    }

    /**
     * @return array
     */
    public function breadcrumbs ()
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
                $returnUrl = Yii::$app->request->get('returnUrl');
                if ($returnUrl !== null) {
                    return $this->controller->redirect($returnUrl);
                } else {
                    return $this->controller->goBack();
                }
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
    public function title ()
    {
        return Yii::t('users', 'Login to site');
    }
}
