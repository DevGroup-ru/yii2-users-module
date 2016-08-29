<?php

use yii\db\Migration;
use DevGroup\Users\models\User;

class m160823_102339_init_roles_and_permissions extends Migration
{
    private static $modulePermissions = [
        'UsersAccountsManager' => [
            'descr' => 'Users Accounts Manager',
            'permits' => [
                'users-user-view' => 'List Users',
                'users-user-edit' => 'Edit Users',
                'users-user-activate' => 'Activate Users',
                'users-user-block' => 'Block Users',
                'users-user-unblock' => 'Unblock Users',
            ]
        ],
        'UsersAdministrator' => [
            'descr' => 'Users Administrator',
            'permits' => [
                'users-user-create' => 'Create Users',
                'users-user-delete' => 'Delete Users',

                'users-role-delete' => 'Delete Roles',
                'users-role-view' => 'List Roles',
                'users-role-edit' => 'Edit Roles',
                'users-role-assign' => 'Assign Roles to User',

                'users-permission-delete' => 'Delete Permissions',
                'users-permission-view' => 'List Permissions',
                'users-permission-edit' => 'Edit Permissions',
                'users-permission-assign' => 'Assign Permissions to Role in Current Module',
            ],
            'roles' => [
                'UsersAccountsManager'
            ],
        ],
        'CoreAdministrator' => [
            'roles' => [
                'UsersAdministrator'
            ]
        ]
    ];

    private static $corePermissions = [
        'CoreAdministrator' => [
            'descr' => 'Core Administrator',
            'permits' => [
                'backend-manage' => 'All backend management operations',
                'backend-view' => 'List backend sections',
                'backend-detail-view' => 'View detail backend item data'
            ],
        ],
        'CoreSuperAdministrator' => [
            'descr' => 'Core Super Administrator',
            'roles' => [
                'CoreAdministrator',
            ],
        ],
    ];

    public function up()
    {
        $this->addColumn(
            User::tableName(),
            'password_is_temporary',
            $this->boolean()->notNull()->defaultValue(false)
        );

        $this->delete(User::tableName(), ['username' => 'admin']);
        $this->insert(
            User::tableName(),
            [
                'username' => 'admin',
                'email' => 'example@noreply.com',
                'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
                'password_is_temporary' => true,
            ]
        );
        $adminId = Yii::$app->db->lastInsertID;

        //core permissions
        self::createPermissions(self::$corePermissions);

        //module permissions
        self::createPermissions(self::$modulePermissions);
        $auth = Yii::$app->authManager;
        $root = $auth->getRole('CoreSuperAdministrator');
        if (null !== $root) {
            $auth->assign($root, $adminId);
        }
    }

    /**
     * @param array $data
     */
    private static function createPermissions(array $data)
    {
        $createdMap = [];
        $auth = Yii::$app->authManager;
        foreach ($data as $roleName => $roleData) {
            $canProcess = false;
            $role = $auth->getRole($roleName);
            if (false === isset($roleData['descr']) && null === $role) {
                continue;
            } elseif (true === isset($roleData['descr']) && null === $role) {
                $role = $auth->createRole($roleName);
                $role->description = $roleData['descr'];
                $canProcess = $auth->add($role);
            } elseif (null !== $role) {
                $canProcess = true;
            }
            if (true === $canProcess) {
                $createdMap[$roleName] = $role;
                if (true === isset($roleData['permits'])) {
                    foreach ($roleData['permits'] as $permName => $permDescr) {
                        $canAdd = true;
                        if (true === isset($createdMap[$permName])) {
                            $permission = $createdMap[$permName];
                        } else {
                            if (null === $permission = $auth->getPermission($permName)) {
                                $permission = $auth->createPermission($permName);
                                $permission->description = $permDescr;
                                $canAdd = $auth->add($permission);
                            }
                        }
                        if ($permission instanceof \yii\rbac\Item && true === $canAdd) {
                            $auth->addChild($role, $permission);
                        }
                    }
                }
                if (true === isset($roleData['roles'])) {
                    foreach ($roleData['roles'] as $roleName) {
                        if (true === isset($createdMap[$roleName])
                            && (true === $createdMap[$roleName] instanceof \yii\rbac\Item)
                        ) {
                            $auth->addChild($role, $createdMap[$roleName]);
                        }
                    }
                }
            }
        }
    }

    public function down()
    {
        $this->dropColumn(User::tableName(), 'password_is_temporary');
        $auth = Yii::$app->authManager;
        $permissions = [];
        foreach (array_column(self::$corePermissions, 'permits') as $data) {
            $permissions = array_merge($permissions, array_keys($data));
        }
        foreach (array_column(self::$modulePermissions, 'permits') as $data) {
            $permissions = array_merge($permissions, array_keys($data));
        }
        foreach ($permissions as $name) {
            $item = $auth->getPermission($name);
            if (null !== $item) {
                $auth->remove($item);
            }
        }
        $roles = array_merge(
            array_keys(self::$corePermissions),
            array_keys(self::$modulePermissions)
        );
        foreach ($roles as $name) {
            $item = $auth->getRole($name);
            if (null !== $item) {
                $auth->remove($item);
            }
        }
        $this->delete(User::tableName(), ['username' => 'admin']);
    }
}
