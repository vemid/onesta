<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Console\Command;

use DI\ContainerBuilder;
use Symfony\Component\Console\Command\Command;

/**
 * Class BaseCommand
 * @package Vemid\ProjectOne\Common\Console\Command
 */
class CommandBase extends Command
{
    /** @var \DI\Container  */
    protected $container;

    /**
     * BaseCommand constructor.
     * @param string|null $name
     * @throws \Exception
     */
    public function __construct(string $name = 'command')
    {
        $this->container = $this->getContainer();

        parent::__construct($name);
    }

    /**
     * @return \DI\Container
     * @throws \Exception
     */
    private function getContainer()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(false);
        $containerBuilder->addDefinitions(sprintf('%s/config/dependencies.php', APP_PATH));

        return $containerBuilder->build();
    }
}
