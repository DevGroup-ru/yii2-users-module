<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\User;
use DevGroup\Users\UsersModule;
use Yii;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;

/**
 * Class EditUser
 *
 * @package DevGroup\Users\actions
 */
class EditUser extends BaseAdminAction
{
    public $viewFile = 'edit-user';

    public function run($id = null, $returnUrl = null)
    {
        /** @var User $user */
        $user = Yii::createObject(ModelMapHelper::User());
        if (null !== $id && null === $user = $user::findOne($id)) {
            throw new NotFoundHttpException(
                Yii::t('users', "{model} with id :'{id}' not found!", [
                    'model' => Yii::t('users', 'User'),
                    'id' => $id
                ])
            );
        }
        $post = Yii::$app->request->post();
        $authManager = Yii::$app->getAuthManager();
        $roles = $currentRoles = [];
        $module = UsersModule::module();
        /**
         * @var string $name
         * @var  Item $role
         */
        foreach ($authManager->getRoles() as $name => $role) {
            $roles[$name] = $name . (empty($role->description) ? '' : " [{$role->description}] ");
        }
        $assigned = $authManager->getRolesByUser($user->id);
        $assignedNames = array_keys($assigned);
        if (false === empty($post)) {
            if (true === $user->load($post) && true === $user->save()) {
                $assignments = empty($post['assignments']) ? [] : $post['assignments'];
                if ($assignments != $assignedNames) {
                    $authManager->revokeAll($user->id);
                    foreach ($assignments as $newName) {
                        if (null !== $role = $authManager->getRole($newName)) {
                            $authManager->assign($role, $user->id);
                            $currentRoles[] = $role->name;
                        } else {
                            Yii::$app->session->setFlash('warning',
                                Yii::t('users', "Unknown role '{$role}'", ['role' => $role])
                            );
                        }
                    }
                }
                Yii::$app->session->setFlash('success',
                    Yii::t('users', '{model} successfully saved!', ['model' => Yii::t('users', 'User')])
                );
                return $this->controller->redirect(['/users/manage/edit', 'id' => $user->id]);
            }
        } else {
            $currentRoles = $assignedNames;
        }
        return $this->render([
            'model' => $user,
            'module' => $module,
            'roles' => $roles,
            'currentRoles' => $currentRoles
        ]);
    }
}