<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Acl\Roles;

return [
    'acl' => [
        'resources' => [
            '', '/', '/404', '/user/management', '/user/profile', '/auth/logout', '/auth/login', '/auth/g2fa',
            '/auth/reset-password', '/auth/new-password', '/auth/change-password', '/auth/g2fa-setup'
        ],
        'assignments' => [
            Roles::GUEST => ['/404', '/auth/login', '/auth/g2fa-setup', '/auth/g2fa', '/auth/reset-password', '/auth/new-password', '/auth/change-password'],
            Roles::ADMIN => '*'
        ],
    ]
];
