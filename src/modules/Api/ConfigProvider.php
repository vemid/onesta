<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Api;

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
            'context'       => Context::JSON
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [];
    }
}