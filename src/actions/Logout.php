<?php

namespace DevGroup\Users\actions;

use DevGroup\Frontend\RedirectHelper;
use Yii;
use yii\base\Action;

class Logout extends Action
{
    public function run()
    {
        Yii::$app->user->logout();

        $returnUrl = RedirectHelper::getReturnUrl();
        if ($returnUrl !== null) {
            return $this->controller->redirect($returnUrl);
        } else {
            return $this->controller->goBack();
        }
    }
}
