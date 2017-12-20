<?php

namespace app\components\rbac;

/**
 * Class Roles
 * @package app\components\rbac
 * @depends Permissions
 */
class Roles
{
    const USER_MANAGEMENT = [
        'role' => 'userManagement',
        'permissions' => [
            Permissions::USER_CREATE,
            Permissions::USER_UPDATE,
            Permissions::USER_DELETE,
            Permissions::USER_INDEX
        ]
    ];
}