<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Middleware\MiddlewareResponseInterface;
use DI\ContainerBuilder;
use Narrowspark\HttpEmitter\SapiEmitter;
use Phoundation\ErrorHandling\RunnerInterface;

ini_set('memory_limit', '1024M');

ob_start('ob_gzhandler');

if (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false) {
    ini_set('session.auto_start', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
}

define('APP_PATH', __DIR__ . '/..');

require_once APP_PATH . '/vendor/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'use_cookies' => true,
        'use_only_cookies' => true,
        'use_strict_mode' => 1,
    ]);
}

\Locale::setDefault('sr');

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(sprintf('%s/config/dependencies.php', APP_PATH));

$container = $containerBuilder->build();
$errorHandler = $container->call($container->get(RunnerInterface::class));
$errorHandler->register();

$emitter = $container->get(SapiEmitter::class);
$emitter->emit($container->get(MiddlewareResponseInterface::class));

if (ob_get_length()) ob_end_clean();
