<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Vemid\ProjectOne\Admin\Handler\Authentication;
use Vemid\ProjectOne\Admin\Handler\AuthenticationWrite;
use Vemid\ProjectOne\Admin\Handler\Code;
use Vemid\ProjectOne\Admin\Handler\CodeWrite;
use Vemid\ProjectOne\Admin\Handler\Product;
use Vemid\ProjectOne\Admin\Handler\ProductWrite;
use Vemid\ProjectOne\Admin\Handler\Supplier;
use Vemid\ProjectOne\Admin\Handler\SupplierReceipt;
use Vemid\ProjectOne\Admin\Handler\SupplierReceiptItem;
use Vemid\ProjectOne\Admin\Handler\SupplierReceiptWrite;
use Vemid\ProjectOne\Admin\Handler\SupplierWrite;
use Vemid\ProjectOne\Admin\Handler\User;
use Vemid\ProjectOne\Api\Handler\Ping;
use Vemid\ProjectOne\Admin\Handler\Index as AdminIndex;
use Vemid\ProjectOne\Form\Handler\Client;
use Vemid\ProjectOne\Form\Handler\User as JsonUser;
use Vemid\ProjectOne\Form\Handler\UserWrite as JsonUserWrite;
use \Vemid\ProjectOne\Admin\Handler\Purchase;

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
        $routeCollector->addRoute('GET', '/suppliers/{method}[/{id:[\w-]+}]', Supplier::class);
        $routeCollector->addRoute('POST', '/suppliers/{method}[/{id:[\w-]+}]', SupplierWrite::class);
        $routeCollector->addRoute('GET', '/codes/{method}[/{id:[\w-]+}]', Code::class);
        $routeCollector->addRoute('POST', '/codes/{method}[/{id:[\w-]+}]', CodeWrite::class);
        $routeCollector->addRoute('GET', '/purchases/{method}[/{id:[\w-]+}]', Purchase::class);
        $routeCollector->addRoute('POST', '/purchases/{method}[/{id:[\w-]+}]', CodeWrite::class);
        $routeCollector->addRoute('GET', '/supplier-receipts/{method}[/{id:[\w-]+}]', SupplierReceipt::class);
        $routeCollector->addRoute('POST', '/supplier-receipts/{method}[/{id:[\w-]+}]', SupplierReceiptWrite::class);
        $routeCollector->addRoute('GET', '/supplier-receipt-items/{method}[/{id:[\w-]+}]', SupplierReceiptItem::class);
        $routeCollector->addRoute('POST', '/supplier-receipt-items/{method}[/{id:[\w-]+}]', SupplierReceiptWrite::class);

        $routeCollector->addGroup('/form', static function (RouteCollector $routeCollector) {
            $routeCollector->addRoute('GET', '/user/{method}[/{id:[\w-]+}]', JsonUser::class);
            $routeCollector->addRoute('GET', '/clients/{method}[/{id:[\w-]+}]', Client::class);
            $routeCollector->addRoute('GET', '/files/{method}/{id}', \Vemid\ProjectOne\Form\Handler\File::class);
            $routeCollector->addRoute('POST', '/user/{method}[/{id:[\w-]+}]', JsonUserWrite::class);
            $routeCollector->addRoute('POST', '/products/{method}[/{id:[\w-]+}]', \Vemid\ProjectOne\Form\Handler\ProductWrite::class);
            $routeCollector->addRoute('POST', '/suppliers/{method}[/{id:[\w-]+}]', \Vemid\ProjectOne\Form\Handler\SupplierWrite::class);
            $routeCollector->addRoute('POST', '/codes/{method}[/{id:[\w-]+}]', \Vemid\ProjectOne\Form\Handler\CodeWrite::class);
            $routeCollector->addRoute('POST', '/supplier-receipts/{method}[/{id:[\w-]+}]', \Vemid\ProjectOne\Form\Handler\SupplierReceiptWrite::class);
        });
    }
];
