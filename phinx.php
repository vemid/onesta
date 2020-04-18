<?php


use Vemid\ProjectOne\Common\Factory\ApplicationConfigFactory;

require __DIR__ . '/vendor/autoload.php';

define('APP_PATH', __DIR__);

$config = ApplicationConfigFactory::create();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/sql/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/sql/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'app_schema_versions',
        'local' => [
            'adapter' => 'mysql',
            'host' => $config->get('db')->get('host'),
            'name' => $config->get('db')->get('name'),
            'user' => $config->get('db')->get('username'),
            'pass' => $config->get('db')->get('password'),
            'port' => $config->get('db')->get('port')
        ],
    ],
];
