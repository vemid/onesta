<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use GuzzleHttp\Client;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vemid\ProjectOne\Common\Config\ConfigInterface;

/**
 * Class UserLocaleMiddleware
 * @package Vemid\ProjectOne\Common\Middleware
 */
class UserLocaleMiddleware implements MiddlewareInterface
{
    private const SESSION_ATTRIBUTE = 'session';

    /** @var ConfigInterface */
    private $config;

    /**
     * UserLocaleMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute(self::SESSION_ATTRIBUTE);

        if (!$session->has('user') || !isset($session->get('user')['locale'])) {
            $guzzle = new Client();
            $response = $guzzle->get('https://geoip.supermasita.com/api/v1.0/ip/');
            $json = json_decode((string)$response->getBody(), true);

            $isoCode = strtolower($json['country']['iso_code']);
            $locale = $this->config->get('language')->toArray()[$isoCode] ?? \Locale::getDefault();
            \Locale::setDefault($locale);

            if ($session->has('user')) {
                $user = $session->get('user');
                $user['locale'] = $locale;

                $session->set('user', $user);
            }
        }

        return $handler->handle($request);
    }
}
