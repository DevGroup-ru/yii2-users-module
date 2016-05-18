<?php

namespace DevGroup\Users\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\Users\actions\CreateRbac;
use DevGroup\Users\actions\ManageRbac;
use DevGroup\Users\actions\UpdateRbac;
use yii\rbac\Item;

/**
 * Class RbacController
 * @package DevGroup\Users\controllers
 */
class RbacController extends BaseController
{
    /**
     * This controller just uses actions in extension
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => ManageRbac::class,
            ],
            'create' => [
                'class' => CreateRbac::class,
            ],
            'update' => [
                'class' => UpdateRbac::class,
            ],
        ];
    }


    /**
     *
     */
    public function actionRemoveItems()
    {
        foreach ($_POST['items'] as $item) {
            \Yii::$app->getAuthManager()->remove(new Item(['name' => $item]));
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->getAuthManager()->remove(new Item(['name' => $id]));
        return $this->redirect(['rbac/']);
    }

}