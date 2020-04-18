<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class MiddlewareLogger
 * @package Vemid\ProjectOne\Common\Middleware
 */
class MiddlewareLogger implements MiddlewareLoggerInterface
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * Logger constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
