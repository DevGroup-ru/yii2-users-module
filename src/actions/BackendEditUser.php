<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\BackendCreateForm;
use DevGroup\Users\models\User;
use Yii;
use yii\rbac\Item;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use DevGroup\DataStructure\helpers\PropertiesHelper;

/**
 * Class BackendEditUser
 *
 * @package DevGroup\Users\actions
 */
class BackendEditUser extends BaseAdminAction
{
    /**
     * @var string
     */
    public $viewFile = 'edit-user';

    /**
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws bool
     */
    public function run($id = null)
    {
        /** @var User $userClass */
        $userClass = ModelMapHelper::User()['class'];
        /** @var User $user */
        $user = $userClass::loadModel(
            $id,
            true,
            true,
            86400,
            new NotFoundHttpException(
                Yii::t('users', "{model} with id :'{id}' not found!", [
                    'model' => Yii::t('users', 'User'),
                    'id' => $id
                ])
            ),
            true
        );
        $user->autoSaveProperties = true;
        $model = new BackendCreateForm();
        if (false === $user->getIsNewRecord()) {
            $model->setAttributes($user->attributes);
        } else {
            $model->scenario = BackendCreateForm::SCENARIO_BACKEND_USER_CREATE;
        }
        $roles = [];
        $authManager = Yii::$app->getAuthManager();
        /**
         * @var string $name
         * @var  Item $role
         */
        foreach ($authManager->getRoles() as $name => $role) {
            $roles[$name] = $name . (empty($role->description) ? '' : " [{$role->description}] ");
        }
        $post = Yii::$app->request->post();
        $currentRoles = [];
        $assigned = $authManager->getRolesByUser($user->id);
        $assignedNames = array_keys($assigned);
        $assignments = empty($post['assignments']) ? [] : $post['assignments'];
        $canSave = Yii::$app->user->can('users-user-edit');
        if (false === empty($post)) {
            if (false === $canSave) {
                throw new ForbiddenHttpException(Yii::t(
                    'yii',
                    'You are not allowed to perform this action.'
                ));
            }
            if (true === $model->load($post) && true === $model->validate()) {
                $attributes = $model->attributes;
                unset($attributes['id']);
                $user->setAttributes($attributes);
                if (true === $user->getIsNewRecord()) {
                    $res = $user->register();
                } else {
                    $user->scenario = $userClass::SCENARIO_PROFILE_UPDATE;
                    $res = $user->save();
                    if (isset($post["User"]) && !empty($post["User"])) {
                        $users = [User::find()->where(["id" => $user->id])->one()];
                        $users[0]->setAttributes($post["User"]);
                        PropertiesHelper::storeValues($users);
                    }
                }
                if (false !== $res) {
                    if ($assignments != $assignedNames) {
                        $authManager->revokeAll($user->id);
                        foreach ($assignments as $newName) {
                            if (null !== $role = $authManager->getRole($newName)) {
                                $authManager->assign($role, $user->id);
                                $currentRoles[] = $role->name;
                            } else {
                                Yii::$app->session->setFlash(
                                    'warning',
                                    Yii::t('users', "Unknown role '{$role}'", ['role' => $role])
                                );
                            }
                        }
                    }
                    Yii::$app->session->setFlash(
                        'success',
                        Yii::t('users', '{model} successfully saved!', ['model' => Yii::t('users', 'User')])
                    );
                    return $this->controller->redirect(['/users/users-manage/edit', 'id' => $user->id]);
                } elseif (false === empty($user->errors)) {
                    Yii::$app->session->setFlash('error', Yii::t(
                        'users',
                        'Unable to save {model}! Reason: {reason}',
                        [
                            'model' => Yii::t('users', 'User'),
                            'reason' => implode('; ', $user->errors)
                        ]
                    ));
                }
            }
        } else {
            $currentRoles = $assignedNames;
        }
        return $this->controller->render(
            $this->viewFile,
            [
                'model' => $model,
                'roles' => $roles,
                'user' => $user,
                'currentRoles' => $currentRoles,
                'canSave' => $canSave
            ]
        );
    }
}
