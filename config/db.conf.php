<?php

declare(strict_types=1);

return [
    'db.host' => getenv('ONESTA_DB_HOST') ?: 'dbs',
    'db.name' => getenv('ONESTA_DB_NAME') ?: 'onesta',
    'db.port' => getenv('ONESTA_DB_PORT') ?: '3306',
    'db.username' => getenv('ONESTA_DB_USER') ?: 'root',
    'db.password' => getenv('ONESTA_DB_PASSWORD') ?: 'root',
    'db.debug' => getenv('ONESTA_ENVIRONMENT') ?: true,
    'db.driver' => 'pdo_mysql'
];
