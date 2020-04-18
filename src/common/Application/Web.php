<?php

namespace Vemid\ProjectOne\Common\Application;

use Middlewares\Utils\Dispatcher;
use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class Web
 * @package Vemid\ProjectOne\Common\Application
 */
final class Web extends Application
{
    /** @var ResponseInterface */
    protected $response;

    public function run()
    {
        $this->initDi();
        $this->initEnvironment();
        $this->initDispatcher();
        $this->emitResponse();
    }

    protected function initDispatcher()
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->container->get(RequestHandlerInterface::class);
        $this->response = $dispatcher->dispatch(ServerRequestFactory::fromGlobals());
    }

    protected function emitResponse()
    {
        if (!$this->response) {
            throw new \RuntimeException('No response has been generated from dispatcher');
        }

        $emitter = $this->container->get(SapiEmitter::class);
        $emitter->emit($this->response);
    }
}
