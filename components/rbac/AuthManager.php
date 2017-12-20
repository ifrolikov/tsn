<?php

namespace app\components\rbac;

use app\models\User;
use yii\rbac\PhpManager;

/**
 * Class AuthManager
 * @package app\components\rbac
 */
class AuthManager extends PhpManager
{
    /**
     * @var string|Permissions
     */
    private $permissionClass;
    /**
     * @var string|Roles
     */
    private $roleClass;
    /**
     * @var string|User
     */
    private $userClass;

    /**
     * AuthManager constructor.
     * @param array $config
     * @param string $permissionClass
     * @param string $roleClass
     * @param string $userClass
     */
    public function __construct(array $config = [], string $permissionClass = Permissions::class,
                                string $roleClass = Roles::class, string $userClass = User::class)
    {
        parent::__construct($config);
        $this->permissionClass = $permissionClass;
        $this->roleClass = $roleClass;
        $this->userClass = $userClass;
    }

    /**
     * @param int|string $userId
     * @param string $permissionName
     * @param array $params
     * @return bool
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $permissions = $this->getPermissionsByUser($userId);
        if (!in_array($permissionName, $permissions)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $roleName
     * @return array|\yii\rbac\Permission[]
     */
    public function getPermissionsByRole($roleName)
    {
        $reflection = new \ReflectionClass(Roles::class);
        foreach ($reflection->getConstants() as $constant) {
            if (is_array($constant) && isset($constant['role']) && $constant['role'] == $roleName) {
                return $constant['permissions'] ?? [];
            }
        }
        return [];
    }

    /**
     * @param int|string $userId
     * @return array|\yii\rbac\Permission[]
     */
    public function getPermissionsByUser($userId)
    {
        $user = User::findOne($userId);
        if (!$user) {
            return [];
        }
        $roles = $user->getUserRoles()->select('role')->column();
        if (!$roles) {
            return [];
        }
        $permissions = [];
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $this->getPermissionsByRole($role));
        }
        return $permissions;
    }
}