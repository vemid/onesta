<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Mezzio\Session\LazySession;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

/**
 * Class LoggedUserMiddleware
 * @package Vemid\ProjectOne\Common\Middleware
 */
class LoggedUserMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var LazySession $session */
        $session = $request->getAttribute('session');

        $allowedActions = [
            '/auth/login',
            '/auth/reset-password',
            '/auth/new-password',
            '/auth/change-password',
            '/auth/g2fa-setup',
            '/auth/g2fa'
        ];

        $uri = preg_replace('/\/\d*$/', '', $request->getUri()->getPath());

        if (!$session->has('user') && !in_array($uri, $allowedActions, false)) {
            header('Location: /auth/login ', false, 302);

            return (new Response())
                ->withStatus(302, 'Not Authorized');
        }

        if ($session->has('user') && in_array($uri, $allowedActions, false)) {
            header('Location: /', false, 302);

            return (new Response())
                ->withStatus(302, 'Already Authorized');
        }

        return $handler->handle($request);
    }
}