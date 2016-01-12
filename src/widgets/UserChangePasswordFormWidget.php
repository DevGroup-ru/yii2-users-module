<?php

namespace DevGroup\Users\widgets;

use DevGroup\Users\models\ChangePasswordForm;
use yii\base\Widget;

class UserChangePasswordFormWidget extends Widget
{
    public $viewFile = 'user-change-password';
    public $formOptions = [
        'options' => [
            'class' => 'user-profile-form m-form',
        ],
    ];
    /** @var ChangePasswordForm */
    public $model = null;


    /** @inheritdoc */
    public function run()
    {

        $this->formOptions['action'] = ['@change-password'];

        return $this->render(
            $this->viewFile,
            [
                'model' => $this->model,
                'formOptions' => $this->formOptions,
            ]
        );
    }



}