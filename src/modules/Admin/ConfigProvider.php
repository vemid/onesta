<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin;

use Vemid\ProjectOne\Common\Route\Handler\Context;

/**
 * Class ConfigProvider
 * @package Vemid\ProjectOne\Main
 */
class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'templates'     => $this->getTemplates(),
            'context'       => Context::HTML

        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                '__main__'  =>  __DIR__ . '/Template',
                'index'     =>  __DIR__ . '/Template/index',
                'auth'      =>  __DIR__ . '/Template/auth',
                'error'     =>  __DIR__ . '/Template/error',
                'user'      =>  __DIR__ . '/Template/user',
                'layout'    =>  __DIR__ . '/Template/layout',
                'email'     =>  __DIR__ . '/Template/email',
                'pdf'       =>  __DIR__ . '/Template/pdf',
                'product'   =>  __DIR__ . '/Template/product',
                'supplier'  =>  __DIR__ . '/Template/supplier',
                'code'      =>  __DIR__ . '/Template/code',
                'purchase'  =>  __DIR__ . '/Template/purchase',
                'client'  =>  __DIR__ . '/Template/client',
                'supplier-receipt'      => __DIR__ . '/Template/supplier-receipt',
                'supplier-receipt-item' => __DIR__ . '/Template/supplier-receipt-item',
                'purchase-item' => __DIR__ . '/Template/purchase-item',
                'payment-installment' => __DIR__ . '/Template/payment-installment',
            ],
        ];
    }
}