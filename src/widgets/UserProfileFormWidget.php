<?php

namespace DevGroup\Users\widgets;

use DevGroup\Users\models\User;
use Yii;
use yii\base\Widget;
use yii\web\ServerErrorHttpException;

class UserProfileFormWidget extends Widget
{
    public $viewFile = 'user-profile-form';
    public $formOptions = [
        'options' => [
            'class' => 'user-profile-form m-form',
        ],
    ];
    /** @var User */
    public $user = null;

    /** @inheritdoc */
    public function run()
    {
        if ($this->user === null) {
            $this->user = Yii::$app->user->identity;
            if ($this->user === null) {
                throw new ServerErrorHttpException("No user identity found");
            }
            $this->user->setScenario(User::SCENARIO_PROFILE_UPDATE);
        }

        $this->formOptions['action'] = ['@profile-update'];

        return $this->render(
            $this->viewFile,
            [
                'user' => $this->user,
                'formOptions' => $this->formOptions,
            ]
        );
    }
}
