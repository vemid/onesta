<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Arbor\Exception\ClientErrorInterface;
use Whoops\Handler\Handler;
use Vemid\ProjectOne\Common\Exception\ProvidesHttpStatusCodeInterface;

/**
 * Class SetHttpStatusCodeHandler
 * @package Arbor\ErrorHandling\Whoops
 */
class SetHttpStatusCodeHandler extends Handler
{
    public function handle()
    {
        $error = $this->getException();

        $httpStatusCode = 500;

        if ($error instanceof ProvidesHttpStatusCodeInterface) {
            $httpStatusCode = $error->getHttpStatusCode();
        } elseif ($error instanceof ClientErrorInterface) {
            $httpStatusCode = 400;
        }

        $this->getRun()->sendHttpCode($httpStatusCode);

        return self::DONE;
    }
}
