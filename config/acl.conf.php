<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Acl\Roles;

return [
    'acl' => [
        'resources' => [
            'modules' => [
                '' => [
                    '' => ['404'],
                    'user' => ['management', 'profile', 'logout'],
                    'auth' => ['logout', 'login', 'g2fa', 'reset-password', 'new-password', 'change-password', 'g2fa-setup'],
                    'products' => ['list', 'create', 'update', 'overview'],
                    'suppliers' => ['list', 'create', 'update', 'overview'],
                    'codes' => ['list', 'create', 'update'],
                    'purchases' => ['list', 'create', 'update', 'add-items', 'add-registration', 'registration'],
                    'supplier-receipts' => ['list', 'create', 'update', 'overview'],
                    'supplier-receipt-items' => ['create', 'update'],
                    'purchase-items' => ['update'],
                ],
                'form' => [
                    'user' => ['edit', 'delete', 'create'],
                    'products' => ['delete'],
                    'codes' => ['delete'],
                    'suppliers' => ['delete'],
                    'purchase-items' => ['delete'],
                    'files' => ['download'],
                    'supplier-receipts' => ['delete'],
                    'supplier-products' => ['get-qty'],
                    'supplier-receipt-items' => ['delete'],
                    'clients' => ['fetch-by-term', 'fetch-by-id'],
                ]
            ],
        ],
        'assignments' => [
            Roles::GUEST => ['/404', '/auth/login', '/auth/g2fa-setup', '/auth/g2fa', '/auth/reset-password', '/auth/new-password', '/auth/change-password'],
            Roles::ADMIN => '*'
        ],
    ]
];
