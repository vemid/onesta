<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Application;

/**
 * Class Cli
 * @package Vemid\ProjectOne\Common\Application
 */
final class Cli extends Application
{
    /**
     * Cli constructor.
     */
    public function __construct()
    {
        $this->initDi();
        $this->initEnvironment();
        $this->initErrorHandling();
    }
}
