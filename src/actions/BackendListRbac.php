<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use yii\base\InvalidParamException;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;
use Yii;

/**
 * Class BackendListRbac
 * @package DevGroup\Users\actions
 */
class BackendListRbac extends BaseAdminAction
{

    /**
     * @var string
     */
    public $viewFile = 'list-rbac';

    /**
     * @param $type
     * @return string
     */
    public function run($type = Item::TYPE_ROLE)
    {
        switch ($type) {
            case Item::TYPE_PERMISSION:
                $id = 'permissions';
                $editPermissionName = 'users-permission-edit';
                $deletePermissionName = 'users-permission-delete';
                $query = Yii::$app->getAuthManager()->getPermissions();
                break;
            case Item::TYPE_ROLE:
                $id = 'roles';
                $editPermissionName = 'users-role-edit';
                $deletePermissionName = 'users-role-delete';
                $query = Yii::$app->getAuthManager()->getRoles();
                break;
            default:
                throw new InvalidParamException(Yii::t('users', 'Unknown Item type.'));
        }
        $provider = new ArrayDataProvider([
            'id' => $id,
            'allModels' => $query,
            'sort' => [
                'attributes' => ['name', 'description', 'ruleName', 'createdAt', 'updatedAt'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $rules = \Yii::$app->getAuthManager()->getRules();
        return $this->render(
            [
                'provider' => $provider,
                'type' => $type,
                'isRules' => !empty($rules),
                'gridId' => $id,
                'editPermissionName' => $editPermissionName,
                'deletePermissionName' => $deletePermissionName,
            ]
        );
    }
}
