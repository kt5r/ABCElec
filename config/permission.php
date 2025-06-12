<?php

return [
    'models' => [
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'role_permissions' => 'role_permissions',
    ],

    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache_expiration_time' => DateInterval::createFromDateString('24 hours'),
];