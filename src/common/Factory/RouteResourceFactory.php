<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Vemid\ProjectOne\Common\Config\ConfigInterface;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

/**
 * Class RouteResourceFactory
 * @package Library\Route
 */
class RouteResourceFactory
{
    /**
     * @param ConfigInterface $config
     * @return Dispatcher
     */
    public function create(ConfigInterface $config): Dispatcher
    {
        return simpleDispatcher($config->get('routes'));
    }
}
