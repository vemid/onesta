<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Acl\Roles;

return [
    'acl' => [
        'resources' => [
            '', '/', '/404', '/user/management', '/user/profile', '/auth/logout', '/auth/login', '/auth/g2fa',
            '/auth/reset-password', '/auth/new-password', '/auth/change-password', '/auth/g2fa-setup', '/products/list', '/form/user/create',
            '/form/user/edit', '/form/user/delete', '/products/create', '/form/product/delete', '/products/update', '/products/overview'
        ],
        'assignments' => [
            Roles::GUEST => ['/404', '/auth/login', '/auth/g2fa-setup', '/auth/g2fa', '/auth/reset-password', '/auth/new-password', '/auth/change-password'],
            Roles::ADMIN => '*'
        ],
    ]
];
