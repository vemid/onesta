<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Whoops\Run as Whoops;
use Phoundation\ErrorHandling\RunnerInterface;

/**
 * Class WhoopsRunner
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
final class WhoopsRunner implements RunnerInterface
{
    /**
     * @var Whoops
     */
    private $whoops;

    public function __construct(Whoops $whoops)
    {
        $this->whoops = $whoops;
    }

    public function register()
    {
        $this->whoops->register();
    }

    public function unregister()
    {
        $this->whoops->unregister();
    }

    public function pushHandler(callable $handler)
    {
        $this->whoops->pushHandler($handler);
    }
}
