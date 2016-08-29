<?php

namespace DevGroup\Users\widgets;

use DevGroup\Users\models\ChangePasswordForm;
use yii\base\Widget;
use Yii;

/**
 * Class UserChangePasswordFormWidget
 *
 * @package DevGroup\Users\widgets
 */
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
        $returnUrl = Yii::$app->request->get('returnUrl', '');
        $changePassRoute = ['@change-password'];
        if (false === empty($returnUrl)) {
            $changePassRoute['returnUrl'] = $returnUrl;
        }
        $this->formOptions['action'] = $changePassRoute;
        return $this->render(
            $this->viewFile,
            [
                'model' => $this->model,
                'formOptions' => $this->formOptions,
            ]
        );
    }
}
