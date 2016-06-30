<?php

namespace DevGroup\Users\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\Users\actions\ManageRbac;
use DevGroup\Users\actions\UpdateRbac;
use yii\rbac\Item;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class RbacController
 *
 * @package DevGroup\Users\controllers
 */
class RbacController extends BaseController
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => ManageRbac::class,
            ],
            'create' => [
                'class' => UpdateRbac::class,
            ],
            'update' => [
                'class' => UpdateRbac::class,
            ],
        ];
    }

    /**
     * Respond only on Ajax
     *
     * @throws NotFoundHttpException
     */
    public function actionRemoveItems()
    {
        if (false === Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        $items = Yii::$app->request->post('items', []);
        $authManager = Yii::$app->getAuthManager();
        $removed = 0;
        foreach ($items as $item) {
            try {
                $authManager->remove(new Item(['name' => $item]));
                $removed++;
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('warning',
                    Yii::t('users', "Unknown RBAC item name '{name}'", ['name' => $item])
                );
            }
        }
        if (0 !== $removed) {
            Yii::$app->session->setFlash('info',
                Yii::t('users', "Items removed: {count}", ['count' => $removed])
            );
        }
    }

    /**
     * Deletes an existing RBAC Item model.
     *
     * @param integer $id
     * @param string $returnUrl
     * @return mixed
     */
    public function actionDelete($id, $returnUrl = '')
    {
        try {
            Yii::$app->getAuthManager()->remove(new Item(['name' => $id]));
            Yii::$app->session->setFlash('info',
                Yii::t('users', "Item successfully removed")
            );
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('warning',
                Yii::t('users', "Unknown RBAC item name '{name}'", ['name' => $id])
            );
        }
        return $this->redirect($returnUrl);
    }

}