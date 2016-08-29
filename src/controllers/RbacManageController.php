<?php

namespace DevGroup\Users\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\Users\actions\BackendListRbac;
use DevGroup\Users\actions\BackendEditRbac;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class RbacController
 *
 * @package DevGroup\Users\controllers
 */
class RbacManageController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['users-permission-view', 'users-role-view'],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            'users-role-edit',
                            'users-permission-edit',
                            'backend-detail-view'
                        ],
                    ],
                    [
                        'actions' => ['delete', 'remove-items'],
                        'allow' => true,
                        'roles' => [
                            'users-role-delete',
                            'users-permission-delete',
                        ],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['*'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'remove-items' => ['POST'],
                ],
            ]
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => BackendListRbac::class,
            ],
            'edit' => [
                'class' => BackendEditRbac::class,
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
            throw new NotFoundHttpException(Yii::t('users', 'Page not found'));
        }
        $type = Yii::$app->request->post('item-type', 0);
        self::checkPermissions($type);
        $items = Yii::$app->request->post('items', []);
        $authManager = Yii::$app->getAuthManager();
        $removed = 0;
        foreach ($items as $item) {
            try {
                $authManager->remove(new Item(['name' => $item]));
                $removed++;
            } catch (\Exception $e) {
                Yii::$app->session->setFlash(
                    'warning',
                    Yii::t('users', "Unknown RBAC item name '{name}'", ['name' => $item])
                );
            }
        }
        if (0 !== $removed) {
            Yii::$app->session->setFlash(
                'info',
                Yii::t('users', "Items removed: {count}", ['count' => $removed])
            );
        }
    }

    /**
     * Deletes an existing RBAC Item model.
     *
     * @param integer $id
     * @param $type
     * @param string $returnUrl
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id, $type, $returnUrl = '')
    {
        self::checkPermissions($type);
        if (true === Yii::$app->getAuthManager()->remove(new Item(['name' => $id]))) {
            Yii::$app->session->setFlash(
                'info',
                Yii::t('users', "Item successfully removed")
            );
        } else {
            Yii::$app->session->setFlash(
                'warning',
                Yii::t('users', "Unknown RBAC item name '{name}'", ['name' => $id])
            );
        }
        $returnUrl = empty($returnUrl) ? ['/users/rbac-manage/index', 'type' => $type] : $returnUrl;
        return $this->redirect($returnUrl);
    }

    /**
     * @param $type
     * @throws ForbiddenHttpException
     */
    private static function checkPermissions($type)
    {
        switch ($type) {
            case Item::TYPE_PERMISSION :
                if (false === Yii::$app->user->can('users-permission-delete')) {
                    throw new ForbiddenHttpException(Yii::t(
                        'yii',
                        'You are not allowed to perform this action.'
                    ));
                }
                break;
            case Item::TYPE_ROLE :
                if (false === Yii::$app->user->can('users-role-delete')) {
                    throw new ForbiddenHttpException(Yii::t(
                        'yii',
                        'You are not allowed to perform this action.'
                    ));
                }
                break;
            default :
                throw new InvalidParamException(Yii::t(
                    'users',
                    'Attempting to delete unknown type of record.'
                ));
        }
    }
}
