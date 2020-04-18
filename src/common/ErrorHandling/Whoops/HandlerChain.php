<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\ErrorHandling\Whoops;

use Whoops\Exception\Inspector;
use Whoops\Handler\Handler;

/**
 * Class HandlerChain
 * @package Vemid\ProjectOne\ErrorHandling\Whoops
 */
final class HandlerChain extends Handler
{
    /**
     * @var Handler[]
     */
    private $handlers;

    public function __construct(Handler ...$handlers)
    {
        $this->handlers = $handlers;
    }

    public function handle()
    {
        $response = null;

        $exception = $this->getException();

        foreach ($this->handlers as $handler) {
            $handler->setRun($this->getRun());
            $handler->setInspector(new Inspector($exception));
            $handler->setException($exception);

            $response = $handler->handle();

            $exception = $handler->getException();

            if (\in_array($response, [Handler::LAST_HANDLER, Handler::QUIT], true)) {
                return $response;
            }
        }

        return $response;
    }

    public function contentType()
    {
        foreach ($this->handlers as $handler) {
            if (method_exists($handler, 'contentType')) {
                return $handler->contentType();
            }
        }

        return null;
    }
}
