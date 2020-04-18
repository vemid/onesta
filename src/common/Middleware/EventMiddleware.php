<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use League\Event\EmitterInterface;
use League\Event\ListenerProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class EventMiddleware
 * @package Vemid\ProjectOne\Common\Middleware
 */
class EventMiddleware implements EventMiddlewareInterface
{

    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    /** @var ListenerProviderInterface */
    private $providers;

    /**
     * RegisterEvents constructor.
     * @param EmitterInterface $eventEmitter
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(EmitterInterface $eventEmitter, ListenerProviderInterface $listenerProvider)
    {
        $this->eventEmitter = $eventEmitter;
        $this->providers = $listenerProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->eventEmitter->useListenerProvider($this->providers);

        return $handler->handle($request);
    }
}
