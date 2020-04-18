<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\MiddlewareFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Zend\Stratigility\path;

/**
 * Class AppMiddlewarePipe
 * @package Vemid\ProjectOne\Common\Middleware
 */
class AppMiddlewarePipe implements MiddlewarePipeInterface
{
    /** @var MiddlewarePipeInterface */
    private $pipeline;

    /** @var MiddlewareFactory */
    private $middlewareFactory;

    /**
     * AppMiddlewarePipe constructor.
     * @param MiddlewarePipeInterface $middlewarePipe
     * @param MiddlewareFactory $middlewareFactory
     */
    public function __construct(MiddlewarePipeInterface $middlewarePipe, MiddlewareFactory $middlewareFactory)
    {
        $this->pipeline = $middlewarePipe;
        $this->middlewareFactory = $middlewareFactory;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }

    public function pipe($middlewareOrPath, $middleware = null): void
    {
        $middleware = $middleware ?: $middlewareOrPath;
        $path = $middleware === $middlewareOrPath ? '/' : $middlewareOrPath;

        $middleware = $path !== '/'
            ? path($path, $this->middlewareFactory->prepare($middleware))
            : $this->middlewareFactory->prepare($middleware);

        $this->pipeline->pipe($middleware);
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return $this->pipeline->handle($request);
    }
}
