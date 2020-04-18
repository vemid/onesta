<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Monolog\ErrorHandler;

/**
 * Interface ErrorHandlerInterface
 * @package Vemid\ProjectOne\Common\Factory
 */
interface ErrorHandlerInterface
{
    /**
     * @return ErrorHandler
     */
    public function __invoke(): ErrorHandler;
}
