<?php

namespace DevGroup\Users\widgets;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\LoginForm;
use Yii;
use yii\base\Widget;

class LoginFormWidget extends Widget
{
    public $viewFile = 'login-form';
    public $formOptions = [
        'options' => [
            'class' => 'login-form',
        ],
    ];
    /** @var LoginForm */
    public $model = null;

    /** @inheritdoc */
    public function run()
    {
        if ($this->model === null) {
            $this->model = Yii::createObject(ModelMapHelper::LoginForm());
        }

        $this->formOptions['action'] = ['@login'];

        return $this->render(
            $this->viewFile,
            [
                'model' => $this->model,
                'formOptions' => $this->formOptions,
            ]
        );
    }
}
