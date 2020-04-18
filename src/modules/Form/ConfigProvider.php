<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form;

use Vemid\ProjectOne\Common\Route\Handler\Context;

/**
 * Class ConfigProvider
 * @package Vemid\ProjectOne\Main
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke() : array
    {
        return [
            'context'       => Context::JSON,

        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                '__main__' => __DIR__ . '/Template',
            ],
        ];
    }
}