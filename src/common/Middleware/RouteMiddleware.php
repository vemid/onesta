<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use FastRoute\Dispatcher;
use Mezzio\Template\TemplateRendererInterface;
use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements MiddlewareInterface
{
    /**
     * @var Dispatcher FastRoute dispatcher
     */
    private $router;

    /** @var TemplateRendererInterface */
    private $templateRenderer;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /**
     * @var string Attribute name for handler reference
     */
    private $attribute = 'request-handler';

    /**
     * RouteMiddleware constructor.
     * @param Dispatcher $router
     * @param TemplateRendererInterface $templateRenderer
     * @param ResponseFactoryInterface|null $responseFactory
     */
    public function __construct(Dispatcher $router, TemplateRendererInterface $templateRenderer, ResponseFactoryInterface $responseFactory = null)
    {
        $this->router = $router;
        $this->templateRenderer = $templateRenderer;
        $this->responseFactory = $responseFactory ?: Factory::getResponseFactory();
    }

    /**
     * @param string $attribute
     * @return $this
     */
    public function attribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->dispatch($request->getMethod(), rawurldecode($request->getUri()->getPath()));

        if ($route[0] === Dispatcher::NOT_FOUND) {
            $response = $this->responseFactory->createResponse(404);
            $body = $this->templateRenderer->render('error::404.html.twig');
            $response->getBody()->write((string)trim(preg_replace('/\s\s+/', ' ', $body)));

            return $response;
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->responseFactory->createResponse(405)->withHeader('Allow', implode(', ', $route[1]));
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $request = $this->setHandler($request, $route[1]);

        return $handler->handle($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param $handler
     * @return ServerRequestInterface
     */
    protected function setHandler(ServerRequestInterface $request, $handler): ServerRequestInterface
    {
        return $request->withAttribute($this->attribute, $handler);
    }
}
