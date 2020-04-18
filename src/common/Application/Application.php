<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Application;

use DI\Container;
use DI\ContainerBuilder;
use Phoundation\ErrorHandling\RunnerInterface;

/**
 * Class Application
 * @package Vemid\ProjectOne\Common\Application
 */
abstract class Application
{
    const DEFAULT_TIMEZONE = 'Europe/London';

    /** @var Container */
    protected $container;

    protected function initDi()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(false);
        $containerBuilder->addDefinitions(sprintf('%s/config/dependencies.php', APP_PATH));
        $this->container = $containerBuilder->build();
    }

    protected function initEnvironment()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $this->container->call($this->container->get(RunnerInterface::class));
    }

    protected function initErrorHandling()
    {
        $errorHandler = $this->container->call($this->container->get(RunnerInterface::class));
        $errorHandler->register();
    }

    /**
     * @return Container
     */
    public function getDi(): Container
    {
        return $this->container;
    }

}
