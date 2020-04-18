<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Main;

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
                '__main__'  => __DIR__ . '/Template',
                'index'     => __DIR__ . '/Template/index',
                'error'     => __DIR__ . '/Template/error',
                'layout'    => __DIR__ . '/Template/layout',
                'email'     => __DIR__ . '/Template/email',
            ],
        ];
    }
}
