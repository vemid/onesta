<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Vemid\ProjectOne\Admin\Handler\Authentication;
use Vemid\ProjectOne\Admin\Handler\AuthenticationWrite;
use Vemid\ProjectOne\Admin\Handler\Product;
use Vemid\ProjectOne\Admin\Handler\ProductWrite;
use Vemid\ProjectOne\Admin\Handler\User;
use Vemid\ProjectOne\Api\Handler\Ping;
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
        $routeCollector->addRoute('GET', '/products/{method}[/{id:[\w-]+}]', Product::class);
        $routeCollector->addRoute('POST', '/products/{method}[/{id:[\w-]+}]', ProductWrite::class);

        $routeCollector->addGroup('/form', static function (RouteCollector $routeCollector) {
            $routeCollector->addRoute('GET', '/user/{method}[/{id:[\w-]+}]', JsonUser::class);
            $routeCollector->addRoute('POST', '/user/{method}[/{id:[\w-]+}]', JsonUserWrite::class);
            $routeCollector->addRoute('POST', '/product/{method}[/{id:[\w-]+}]', \Vemid\ProjectOne\Form\Handler\ProductWrite::class);
        });
    }
];
