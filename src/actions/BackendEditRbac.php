<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\models\AuthItemForm;
use Yii;
use yii\base\InvalidParamException;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UpdateRbac
 * @package DevGroup\Users\actions
 */
class BackendEditRbac extends BaseAdminAction
{

    /**
     * @var string
     */
    public $viewFile = 'edit-rbac-item';

    /**
     * Updates or creates RBAC Item
     * Type of item depends of given type
     *
     * @param null | string $id
     * @param int $type
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function run($id = null, $type)
    {
        $roleText = Yii::t('users', 'Role');
        $permText = Yii::t('users', 'Permission');
        if ($type == Item::TYPE_ROLE) {
            $modelName = $roleText;
            $permName = 'users-role-edit';
        } else {
            $modelName = $permText;
            $permName = 'users-permission-edit';
        }
        $authManager = Yii::$app->getAuthManager();
        if (null !== $id) {
            $model = new AuthItemForm();
            switch ($type) {
                case Item::TYPE_PERMISSION:
                    $item = $authManager->getPermission($id);
                    break;
                case Item::TYPE_ROLE:
                    $item = $authManager->getRole($id);
                    break;
                default:
                    throw new InvalidParamException(Yii::t('users', 'Unexpected RBAC Item type'));
            }
            if (null === $item) {
                throw new NotFoundHttpException(
                    Yii::t('users', "{model} with id :'{id}' not found!", [
                        'model' => Yii::t('users', 'RBAC Item'),
                        'id' => $id
                    ])
                );
            }
            $children = $authManager->getChildren($id);
            $selected = array_keys($children);
            $model->name = $item->name;
            $model->oldname = $item->name;
            $model->type = $item->type;
            $model->description = $item->description;
            $model->ruleName = $item->ruleName;
            $model->children = $selected;
            $actionName = "updateItem";
        } else {
            if (true === in_array($type, [Item::TYPE_ROLE, Item::TYPE_PERMISSION])) {
                $model = new AuthItemForm(['isNewRecord' => true]);
                $model->type = $type;
            } else {
                throw new InvalidParamException(Yii::t('users', 'Unexpected RBAC Item type'));
            }
            $actionName = "createItem";
        }
        $post = Yii::$app->request->post();
        if (false === empty($post) && false === Yii::$app->user->can($permName)) {
            throw new ForbiddenHttpException(Yii::t(
                'yii',
                'You are not allowed to perform this action.'
            ));
        }
        if ($model->load($post) && $model->validate()) {
            $item = call_user_func([$model, $actionName]);
            if (false === empty($model->errors)) {
                Yii::$app->getSession()->setFlash('error', $model->getErrorMessage());
            } else {
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('users', '{model} successfully saved!', ['model' => $modelName])
                );
            }
            return $this->controller->redirect(['/users/rbac-manage/edit', 'id' => $item->name, 'type' => $item->type]);
        }

        return $this->render(
            [
                'model' => $model,
                'permName' => $permName,
                'items' => self::getItems($type, $id, $authManager),
            ]
        );
    }

    /**
     * @param int $type
     * @param string $id
     * @param ManagerInterface $authManager
     * @return array
     */
    private static function getItems($type, $id, $authManager)
    {
        $items = [];
        switch ($type) {
            case Item::TYPE_PERMISSION:
                $items = self::prepareItems($authManager->getPermissions(), $id);
                break;
            case Item::TYPE_ROLE:
                $items[Yii::t('users', 'Permissions')] = self::prepareItems($authManager->getPermissions(), $id);
                $items[Yii::t('users', 'Roles')] = self::prepareItems($authManager->getRoles(), $id);
                break;
        }
        return $items;
    }

    /**
     * @param Item[] $data
     * @param string $id
     * @return array
     */
    public static function prepareItems($data, $id)
    {
        $items = [];
        foreach ($data as $name => $role) {
            if ($name == $id) {
                continue;
            }
            $items[$name] = $name . (empty($role->description) ? '' : " [{$role->description}] ");
        }
        return $items;
    }
}
