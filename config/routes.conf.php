<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Vemid\ProjectOne\Admin\Handler\Authentication;
use Vemid\ProjectOne\Admin\Handler\AuthenticationWrite;
use Vemid\ProjectOne\Admin\Handler\User;
use Vemid\ProjectOne\Api\Handler\Ping;
use Vemid\ProjectOne\Main\Handler\Index;
use Vemid\ProjectOne\Admin\Handler\Index as AdminIndex;
use Vemid\ProjectOne\Form\Handler\User as JsonUser;
use Vemid\ProjectOne\Form\Handler\UserWrite as JsonUserWrite;

return [
    'routes' => static function (RouteCollector $routeCollector) {

        $routeCollector->addGroup('/api', static function (RouteCollector $routeCollector) {
            $routeCollector->addRoute('GET', '/ping', Ping::class);
        });

        $routeCollector->addRoute('GET', '', AdminIndex::class);
        $routeCollector->addRoute('GET', '/', AdminIndex::class);
        $routeCollector->addRoute('GET', '/index/{method}[/{id:[\w-]+}]', AdminIndex::class);
        $routeCollector->addRoute('GET', '/auth/{method}[/{id:[\w-]+}]', Authentication::class);
        $routeCollector->addRoute('POST', '/auth/{method}[/{id:[\w-]+}]', AuthenticationWrite::class);
        $routeCollector->addRoute('GET', '/user/{method}[/{id:[\w-]+}]', User::class);

        $routeCollector->addGroup('/form', static function (RouteCollector $routeCollector) {
            $routeCollector->addRoute('GET', '/user/{method}[/{id:[\w-]+}]', JsonUser::class);
            $routeCollector->addRoute('POST', '/user/{method}[/{id:[\w-]+}]', JsonUserWrite::class);
        });
    }
];
