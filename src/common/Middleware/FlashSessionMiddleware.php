<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vemid\ProjectOne\Common\Session\FlashSession;

/**
 * Class FlashSessionMiddleware
 * @package Vemid\ProjectOne\Common\Middleware
 */
class FlashSessionMiddleware implements MiddlewareInterface
{
    public const SESSION_ATTRIBUTE = 'session';
    public const FLASH_SESSION_ATTRIBUTE = 'flashSession';

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $flashSession = new FlashSession($request->getAttribute(self::SESSION_ATTRIBUTE));

        return $handler->handle($request->withAttribute(self::FLASH_SESSION_ATTRIBUTE, $flashSession));
    }
}
