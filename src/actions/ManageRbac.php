<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use yii\data\ArrayDataProvider;

/**
 * Class ManageRbac
 * @package DevGroup\Users\actions
 */
class ManageRbac extends BaseAdminAction
{

    /**
     * @var string
     */
    public $viewFile = 'manage-rbac';

    /**
     * @return string
     */
    public function run()
    {
        $rules = \Yii::$app->getAuthManager()->getRules();
        $permissions = new ArrayDataProvider(
            [
                'id' => 'permissions',
                'allModels' => \Yii::$app->getAuthManager()->getPermissions(),
                'sort' => [
                    'attributes' => ['name', 'description', 'ruleName', 'createdAt', 'updatedAt'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        $roles = new ArrayDataProvider(
            [
                'id' => 'roles',
                'allModels' => \Yii::$app->getAuthManager()->getRoles(),
                'sort' => [
                    'attributes' => ['name', 'description', 'ruleName', 'createdAt', 'updatedAt'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        return $this->render(
            [
                'permissions' => $permissions,
                'roles' => $roles,
                'isRules' => !empty($rules),
            ]
        );
    }


}