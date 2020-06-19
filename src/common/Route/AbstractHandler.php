<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Route;

use Mezzio\Session\SessionInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\ProjectOne\Common\Config\ConfigModuleResolver;
use Vemid\ProjectOne\Common\Factory\StreamLoggerInterface;
use Vemid\ProjectOne\Common\Message\MessageBag;
use Vemid\ProjectOne\Common\Message\MessageInterface;
use Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver\ArgumentResolverManager;
use League\Event\EmitterInterface;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Vemid\ProjectOne\Common\Route\Handler\Context;
use Vemid\ProjectOne\Common\Template\View;
use Vemid\ProjectOne\Common\Translator\TranslationInterface;
use Zend\Diactoros\Response;
use Zend\Expressive\Twig\TwigRenderer;

/**
 * Class AbstractHandler
 * @package Vemid\ProjectOne\Common\Route
 */
abstract class AbstractHandler implements RequestHandlerInterface
{
    /** @var Response */
    private $response;

    /** @var EmitterInterface */
    protected $eventEmitter;

    /** @var ServerRequestInterface */
    protected $request;

    /** @var ArgumentResolverManager */
    private $argumentResolverManager;

    /** @var StreamLoggerInterface|Logger */
    protected $logger;

    /** @var TwigRenderer */
    protected $template;

    /** @var ConfigInterface */
    protected $config;

    /** @var string */
    private $redirectUrl;

    /** @var string */
    private $forward;

    /** @var SessionInterface */
    protected $session;

    /** @var TranslationInterface */
    protected $translator;

    /** @var MessageInterface */
    private $message;

    /** @var MessageBag */
    protected $messageBag;

    /** @var View */
    protected $view;

    /**
     * AbstractHandler constructor.
     * @param ResponseInterface $response
     * @param EmitterInterface $eventEmitter
     * @param ArgumentResolverManager $argumentResolverManager
     * @param LoggerInterface $logger
     * @param TemplateRendererInterface $template
     * @param ConfigInterface $config
     * @param TranslationInterface $translator
     * @param MessageInterface $message
     */
    public function __construct(
        ResponseInterface $response,
        EmitterInterface $eventEmitter,
        ArgumentResolverManager $argumentResolverManager,
        LoggerInterface $logger,
        TemplateRendererInterface $template,
        ConfigInterface $config,
        TranslationInterface $translator,
        MessageInterface $message
    ){
        $this->response = $response;
        $this->eventEmitter = $eventEmitter;
        $this->argumentResolverManager = $argumentResolverManager;
        $this->logger = $logger;
        $this->template = $template;
        $this->config = $config;
        $this->translator = $translator;
        $this->message = $message;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $attributes = $request->getAttributes();
        $this->request = $request;
        $this->view = new View();
        $this->session = $attributes['session'];
        $this->messageBag = new MessageBag($this->message, $attributes['flashSession']);

        $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'flashSession', $attributes['flashSession']);
        $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'session', $attributes['session']);
        $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'request', $this->request);

        $configModuleResolver = new ConfigModuleResolver($this->config, $this->request);
        $moduleConfig = $configModuleResolver->resolve();

        $method = 'index';
        $id = $attributes['id'] ?? null;

        if (array_key_exists('method', $attributes))  {
            $method = ucwords(trim(preg_replace('/[^a-z0-9]+/i', ' ', $attributes['method'])));
            $method = lcfirst(str_replace(' ', '', $method));
        }

        if (!method_exists($this, $method)) {
            $this->response->withHeader('Content-Type', $this->switchContext($moduleConfig['context']));
            if ($moduleConfig['context'] === 'html') {
                $body = $this->template->render('error::404.html.twig');
                $this->response->getBody()->write((string)trim(preg_replace('/\s\s+/', ' ', $body)));
            }

            return $this->response->withStatus(404, 'Page Not Found');
        }

        $arguments = $this->argumentResolverManager->resolve($this, $method);
        if (count($arguments) && !$arguments[0])    {
            $arguments[0] = $id;
        }

        $isAjax = 'XMLHttpRequest' === ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');

        $body = $this->$method(...$arguments);
        if ($body instanceof ResponseInterface) {
            return $body;
        }

        if ($this->redirectUrl){
            if (!$isAjax && $moduleConfig['context'] !== Context::JSON) {
                header(sprintf('Location: %s', $this->redirectUrl), false, 301);
                $this->messageBag->emptyBag(($isAjax || $moduleConfig['context'] === 'json' ? 'json' : 'html'), $body);

                return (new Response())
                    ->withStatus(302, 'Redirection');
            }

            $body['url'] = $this->redirectUrl;
        }

        if ($this->forward){
            if (!$isAjax && $moduleConfig['context'] !== Context::JSON) {
               //TODO we should call internal action here and pass body to request
            } else {
                $body['next'] = $this->forward;
            }
        }

        $this->messageBag->emptyBag(($isAjax || $moduleConfig['context'] === 'json' ? 'json' : 'html'), $body);

        if (!empty(array_filter($this->view->getTemplate()))) {
            list($template, $params) = array_values($this->view->getTemplate());
            $body = $this->template->render($template, $params);
        }

        if (($isAjax || $moduleConfig['context'] === Context::JSON) && is_array($body)) {
            $body = json_encode($body);
        }

        $this->response->withHeader('Content-Type', $this->switchContext($moduleConfig['context']));
        $this->response->withHeader('Content-Encoding', 'gzip');
        $this->response->getBody()->write((string)trim(preg_replace('/\s\s+/', ' ', $body)));
        $this->response->getBody()->rewind();

        if ($this->response->getBody() !== null && !$this->response->hasHeader('Content-Length')) {
            $this->response->withHeader('Content-Length', (string) $this->response->getBody());
            $allHeaders = getallheaders();

            if (!empty($allHeaders['Require-Auth'])) {
                header('Require-Auth: 1');
                $this->response->withHeader('Require-Auth', 1);
            }
        }

        return $this->response;
    }

    /**
     * @param string $context
     * @return string
     */
    private function switchContext(string $context): string
    {
        switch ($context) {
            case Context::HTML:
            default:
                return 'text/html; charset=utf-8';
                break;
            case Context::JSON:
                return 'application/json';
                break;
        }
    }

    /**
     * @param string $redirectUrl
     */
    protected function redirect(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @param string $url
     */
    protected function forward(string $url): void
    {
        $this->forward = $url;
    }
}
