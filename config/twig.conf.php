<?php

declare(strict_types=1);

return [
    'templates' => [
        'cache' => false,
        'cache_dir' => APP_PATH . '/var/cache',
        'cache_enabled' => false,
        'external' => [
            'path' => APP_PATH . '/public/assets',
            'path_chmod' => -1,
            'url_base_path' => '/assets/',
            'cache_path' => APP_PATH . '/var/cache',
            'cache_name' => 'assets-cache',
            'cache_lifetime' => 0,
            'minify' => 1
        ]
    ],
];
