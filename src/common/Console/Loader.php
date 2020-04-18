<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Vemid\ProjectOne\Common\Helper\Directory;

/**
 * Class Application
 *
 * @package Arbor\Crm\Console
 */
class Loader extends Application
{
    /**
     * @param string $version
     */
    public function __construct($version)
    {
        parent::__construct('Vemid Console', $version);

        $this->addCommands($this->getCommands());
    }

    /**
     * @return array
     */
    protected function getCommands(): array
    {
        $commands = [];
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'Command';

        foreach (Directory::listClasses($dir, true) as $className) {
            if (!class_exists($className)) {
                continue;
            }

            $command = new $className;
            if (!$command instanceof Command) {
                continue;
            }

            $commands[] = $command;
        }

        return $commands;
    }
}
