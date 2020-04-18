<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class HttpAuthentication
 * @package Vemid\ProjectOne\Common\Middleware
 */
class HttpAuthentication implements MiddlewareInterface
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /**
     * @var string|null
     */
    protected $attribute = 'userId';

    /**
     * @var array
     */
    protected $users;

    /**
     * @var string
     */
    protected $realm = 'Login';

    /**
     * HttpAuthentication constructor.
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $username = $this->login($request);

        if ($username === false) {
            return $this->responseFactory->createResponse(401)
                ->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
        }

        if ($this->attribute !== null) {
            $request = $request->withAttribute($this->attribute, $username);
        }

        return $handler->handle($request);
    }

    /**
     * @param string $realm
     * @return HttpAuthentication
     */
    public function realm(string $realm): self
    {
        $this->realm = $realm;

        return $this;
    }

    /**
     * @param string $attribute
     * @return HttpAuthentication
     */
    public function attribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool|int
     */
    private function login(ServerRequestInterface $request)
    {
        $authorization = $this->parseHeader($request->getHeaderLine('Authorization'));

        if (!$authorization) {
            return false;
        }

        return true;
    }

    /**
     * @param string $header
     * @return array|null
     */
    private function parseHeader(string $header): ?array
    {
        if (strpos($header, 'Basic') !== 0) {
            return null;
        }

        $header = explode(':', base64_decode(substr($header, 6)), 2);

        return [
            'username' => $header[0],
            'password' => isset($header[1]) ? $header[1] : null,
        ];
    }
}
