<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Session;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Mezzio\Session\InitializePersistenceIdInterface;
use Mezzio\Session\Session;
use Mezzio\Session\SessionCookiePersistenceInterface;
use Mezzio\Session\SessionInterface;
use Mezzio\Session\SessionPersistenceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PhpSessionPersistence
 * @package Vemid\ProjectOne\Common\Session
 */
class PhpSessionPersistence implements InitializePersistenceIdInterface, SessionPersistenceInterface
{
    /**
     * Use non locking mode during session initialization?
     *
     * @var bool
     */
    private $nonLocking;

    /**
     * The time-to-live for cached session pages in minutes as specified in php
     * ini settings. This has no effect for 'nocache' limiter.
     *
     * @var int
     */
    private $cacheExpire;

    /**
     * The cache control method used for session pages as specified in php ini
     * settings. It may be one of the following values: 'nocache', 'private',
     * 'private_no_expire', or 'public'.
     *
     * @var string
     */
    private $cacheLimiter;

    /** @var array */
    private static $supported_cache_limiters = [
        'nocache'           => true,
        'public'            => true,
        'private'           => true,
        'private_no_expire' => true,
    ];

    /**
     * This unusual past date value is taken from the php-engine source code and
     * used "as is" for consistency.
     */
    public const CACHE_PAST_DATE  = 'Thu, 19 Nov 1981 08:52:00 GMT';

    public const HTTP_DATE_FORMAT = 'D, d M Y H:i:s T';

    /**
     * PhpSessionPersistence constructor.
     * @param bool $nonLocking
     */
    public function __construct(bool $nonLocking = false)
    {
        $this->nonLocking = $nonLocking;

        $this->cacheLimiter = ini_get('session.cache_limiter');
        $this->cacheExpire  = (int) ini_get('session.cache_expire');
    }

    /**
     * @param ServerRequestInterface $request
     * @return SessionInterface
     */
    public function initializeSessionFromRequest(ServerRequestInterface $request) : SessionInterface
    {
        $sessionId = FigRequestCookies::get($request, session_name())->getValue() ?? '';
        if ($sessionId && session_status() !== PHP_SESSION_ACTIVE) {
            $this->startSession($sessionId, [
                'read_and_close' => $this->nonLocking,
            ]);
        }

        return new Session($_SESSION ?? [], $sessionId);
    }

    /**
     * @param SessionInterface $session
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function persistSession(SessionInterface $session, ResponseInterface $response) : ResponseInterface
    {
        $id = $session->getId();
        if ($session->isRegenerated()
            || ('' === $id && $session->hasChanged())
        ) {
            $id = $this->regenerateSession();
        } elseif ($this->nonLocking && $session->hasChanged()) {
            $this->startSession($id);
        }

        if ('' === $id || !$session->hasChanged()) {
            return $response;
        }

        if (PHP_SESSION_ACTIVE === session_status()) {
            session_id($id);
            $_SESSION = $session->toArray();
            session_write_close();
        }

        $response = $this->addSessionCookie($response, $id, $session);
        $response = $this->addCacheHeaders($response);

        return $response;
    }

    /**
     * @param SessionInterface $session
     * @return SessionInterface
     */
    public function initializeId(SessionInterface $session): SessionInterface
    {
        $id = $session->getId();
        if ('' === $id || $session->isRegenerated()) {
            $session = new Session($session->toArray(), $this->generateSessionId());
        }

        session_id($session->getId());

        return $session;
    }

    /**
     * @param string $id
     * @param array $options
     */
    private function startSession(string $id, array $options = []) : void
    {
        if( strpos( $_SERVER['SERVER_SOFTWARE'], 'Apache') !== false && session_status() === PHP_SESSION_ACTIVE) {
            session_abort();
        }

        session_id($id);
        session_start([
                'use_cookies'      => true,
                'use_only_cookies' => true,
                'cache_limiter'    => '',
            ] + $options);
    }

    /**
     * @return string
     */
    private function regenerateSession() : string
    {
        session_write_close();
        $id = $this->generateSessionId();
        $this->startSession($id, [
            'use_strict_mode' => false,
        ]);
        return $id;
    }

    /**
     * Generate a session identifier.
     */
    private function generateSessionId() : string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * @param ResponseInterface $response
     * @param string $id
     * @param SessionInterface $session
     * @return ResponseInterface
     */
    private function addSessionCookie(
        ResponseInterface $response,
        string $id,
        SessionInterface $session
    ) : ResponseInterface {
        return FigResponseCookies::set(
            $response,
            $this->createSessionCookie(session_name(), $id, $this->getCookieLifetime($session))
        );
    }

    /**
     * @param string $name
     * @param string $id
     * @param int $cookieLifetime
     * @return SetCookie
     */
    private function createSessionCookie(string $name, string $id, int $cookieLifetime = 0) : SetCookie
    {
        $secure = filter_var(
            ini_get('session.cookie_secure'),
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );
        $httpOnly = filter_var(
            ini_get('session.cookie_httponly'),
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );

        $sessionCookie = SetCookie::create($name)
            ->withValue($id)
            ->withPath(ini_get('session.cookie_path'))
            ->withDomain(ini_get('session.cookie_domain'))
            ->withSecure($secure)
            ->withHttpOnly($httpOnly);

        return $cookieLifetime
            ? $sessionCookie->withExpires(time() + $cookieLifetime)
            : $sessionCookie;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function addCacheHeaders(ResponseInterface $response) : ResponseInterface
    {
        if (! $this->cacheLimiter || $this->responseAlreadyHasCacheHeaders($response)) {
            return $response;
        }

        $cacheHeaders = $this->generateCacheHeaders();
        foreach ($cacheHeaders as $name => $value) {
            if (false !== $value) {
                $response = $response->withHeader($name, $value);
            }
        }

        return $response;
    }

    /**
     * @return array
     */
    private function generateCacheHeaders() : array
    {
        if (! isset(self::$supported_cache_limiters[$this->cacheLimiter])) {
            return [];
        }

        if ('nocache' === $this->cacheLimiter) {
            return [
                'Expires'       => self::CACHE_PAST_DATE,
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma'        => 'no-cache',
            ];
        }

        $maxAge       = 60 * $this->cacheExpire;
        $lastModified = $this->getLastModified();

        // cache_limiter: 'public'
        if ('public' === $this->cacheLimiter) {
            return [
                'Expires'       => gmdate(self::HTTP_DATE_FORMAT, time() + $maxAge),
                'Cache-Control' => sprintf('public, max-age=%d', $maxAge),
                'Last-Modified' => $lastModified,
            ];
        }

        if ('private' === $this->cacheLimiter) {
            return [
                'Expires'       => self::CACHE_PAST_DATE,
                'Cache-Control' => sprintf('private, max-age=%d', $maxAge),
                'Last-Modified' => $lastModified,
            ];
        }

        // last possible case, cache_limiter = 'private_no_expire'
        return [
            'Cache-Control' => sprintf('private, max-age=%d', $maxAge),
            'Last-Modified' => $lastModified,
        ];
    }

    /**
     * @return bool|false|string
     */
    private function getLastModified()
    {
        $lastmod = getlastmod() ?: filemtime(__FILE__);
        return $lastmod ? gmdate(self::HTTP_DATE_FORMAT, $lastmod) : false;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    private function responseAlreadyHasCacheHeaders(ResponseInterface $response) : bool
    {
        return (
            $response->hasHeader('Expires')
            || $response->hasHeader('Last-Modified')
            || $response->hasHeader('Cache-Control')
            || $response->hasHeader('Pragma')
        );
    }

    private function getCookieLifetime(SessionInterface $session) : int
    {
        $lifetime = (int) ini_get('session.cookie_lifetime');
        if ($session instanceof SessionCookiePersistenceInterface
            && $session->has(SessionCookiePersistenceInterface::SESSION_LIFETIME_KEY)
        ) {
            $lifetime = $session->getSessionLifetime();
        }

        return $lifetime > 0 ? $lifetime : 0;
    }
}
