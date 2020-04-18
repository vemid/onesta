<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Whoops\Handler\Handler;

/**
 * Class CommandLineFormatter
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
class CommandLineFormatter extends Handler
{
    public function handle()
    {
        $e = $this->getException();

        $errorString = "+---------------------+\n| AN ERROR HAS OCCURRED |\n+---------------------+\n";
        $errorString .= "%s";
        $errorString .= "Stack Trace:\n%s\n";

        $message = $e->getMessage();
        $trace = $e->getTraceAsString();

        echo sprintf($errorString, $message, $trace);

        return self::QUIT;
    }
}
