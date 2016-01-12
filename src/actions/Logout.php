<?php

namespace DevGroup\Users\actions;

use Yii;
use yii\base\Action;

class Logout extends Action
{
    public function run()
    {
        Yii::$app->user->logout();
        $returnUrl = Yii::$app->request->get('returnUrl');
        if ($returnUrl !== null) {
            return $this->controller->redirect($returnUrl);
        } else {
            return $this->controller->goBack();
        }
    }
}
