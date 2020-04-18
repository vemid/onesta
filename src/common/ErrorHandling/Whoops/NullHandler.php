<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Whoops\Handler\Handler;

/**
 * Class NullHandler
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
final class NullHandler extends Handler
{
    public function handle()
    {
        return self::DONE;
    }
}
