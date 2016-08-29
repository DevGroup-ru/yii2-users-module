<?php

namespace DevGroup\Users\actions;

use Yii;
use yii\base\Action;

/**
 * Class Logout
 *
 * @package DevGroup\Users\actions
 */
class Logout extends Action
{
    /**
     * @return \yii\web\Response
     */
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
