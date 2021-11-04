<?php

declare(strict_types=1);

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Permissions\Acl\AclInterface;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Session\SessionPersistenceInterface;
use Mezzio\Template\TemplateRendererInterface;
use Odan\Twig\TwigAssetsExtension;
use PragmaRX\Google2FA\Google2FA;
use Twig\Environment;
use Vemid\ProjectOne\Admin\Form\UserChangePassword;
use Vemid\ProjectOne\Admin\Form\UserLoginForm;
use Vemid\ProjectOne\Common\Acl\Roles;
use Vemid\ProjectOne\Common\Acl\RolesInterface;
use Vemid\ProjectOne\Common\Config\ConfigModuleResolver;
use Vemid\ProjectOne\Common\Config\ConfigResolvedInterface;
use Vemid\ProjectOne\Common\Event\EventList;
use Vemid\ProjectOne\Common\Event\EventListInterface;
use Vemid\ProjectOne\Common\Event\EventProvider;
use Vemid\ProjectOne\Common\Factory\EntityManagerFactory;
use Vemid\ProjectOne\Common\Factory\ErrorLoggerInterface;
use Vemid\ProjectOne\Common\Factory\EventManagerFactory;
use Vemid\ProjectOne\Common\Factory\StreamLoggerFactory;
use Vemid\ProjectOne\Common\Factory\TranslatorFactory;
use Vemid\ProjectOne\Common\Factory\TwigEnvironmentFactory;
use Vemid\ProjectOne\Common\Factory\TwigFactory;
use Vemid\ProjectOne\Common\Form\Builder\EntityAnnotationReader;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\FileManager;
use Vemid\ProjectOne\Common\Message\Manager;
use Vemid\ProjectOne\Common\Message\MessageInterface;
use Vemid\ProjectOne\Common\Middleware\Acl;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\ProjectOne\Common\Factory\ApplicationConfigFactory;
use Vemid\ProjectOne\Common\Factory\ConnectionFactory;
use Vemid\ProjectOne\Common\Factory\ErrorHandlerFactory;
use Vemid\ProjectOne\Common\Factory\ErrorLoggerFactory;
use Vemid\ProjectOne\Common\Factory\RouteResourceFactory;
use Vemid\ProjectOne\Common\Mailer\MailManager;
use Vemid\ProjectOne\Common\Middleware\AppMiddlewarePipe;
use Vemid\ProjectOne\Common\Middleware\EventMiddlewareInterface;
use Vemid\ProjectOne\Common\Middleware\FlashSessionMiddleware;
use Vemid\ProjectOne\Common\Middleware\HttpAuthentication;
use Vemid\ProjectOne\Common\Middleware\LoggedUserMiddleware;
use Vemid\ProjectOne\Common\Middleware\MiddlewareLogger;
use Vemid\ProjectOne\Common\Middleware\MiddlewareAclInterface;
use Vemid\ProjectOne\Common\Middleware\MiddlewareLoggerInterface;
use Vemid\ProjectOne\Common\Middleware\EventMiddleware;
use Vemid\ProjectOne\Common\Middleware\MiddlewareResponseInterface;
use Vemid\ProjectOne\Common\Middleware\RouteMiddleware;
use Vemid\ProjectOne\Common\Middleware\UserLocaleMiddleware;
use Vemid\ProjectOne\Common\Pdf\PdfBuilder;
use Vemid\ProjectOne\Common\Pdf\PdfBuilderInterface;
use Vemid\ProjectOne\Common\Pdf\Renderer\TcPdf;
use Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver\ServiceArgumentResolver;
use Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver\ArgumentResolverManager;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Phoundation\ErrorHandling\RunnerInterface;
use Vemid\ProjectOne\Common\Session\PhpSessionPersistence;
use Vemid\ProjectOne\Common\Mailer\MailManagerInterface;
use Vemid\ProjectOne\Common\Translator\Translation;
use Vemid\ProjectOne\Common\Translator\TranslationInterface;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Helper\UrlHelperFactory;
use Zend\Expressive\Twig\TwigExtension;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use Doctrine\DBAL\Connection;
use FastRoute\Dispatcher;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use League\Event\ListenerProviderInterface;
use Middlewares\RequestHandler;
use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;

return [
    AclInterface::class => create(\Laminas\Permissions\Acl\Acl::class),
    AppMiddlewarePipe::class => autowire(AppMiddlewarePipe::class),
    ArgumentResolverManager::class => static function (ContainerInterface $container) {
        return new ArgumentResolverManager(
            $container->get(ServiceArgumentResolver::class)
        );
    },
    FormBuilderInterface::class => autowire(EntityAnnotationReader::class),
    ConfigResolvedInterface::class => static function (ContainerInterface $container) {
        $configModuleResolver = $container->get(ConfigModuleResolver::class);
        $config = $container->get(ConfigInterface::class);

        return array_merge_recursive((array)$config, $configModuleResolver->resolve());
    },
    ConfigInterface::class => factory([ApplicationConfigFactory::class, 'create']),
    ConfigModuleResolver::class => autowire(ConfigModuleResolver::class),
    Connection::class => factory([ConnectionFactory::class, 'create']),
    Dispatcher::class => factory([RouteResourceFactory::class, 'create']),
    EmitterInterface::class => create(Emitter::class),
    Environment::class => static function (ContainerInterface $container) {
        return (new TwigEnvironmentFactory())($container->get(ConfigResolvedInterface::class));
    },
    EntityManagerInterface::class => factory([EntityManagerFactory::class, 'create']),
    ErrorLoggerInterface::class => factory([ErrorLoggerFactory::class, 'create']),
    EventMiddlewareInterface::class => autowire(EventMiddleware::class),
    EventListInterface::class => create(EventList::class),
    EventManager::class => factory([EventManagerFactory::class, 'create']),
    Google2FA::class => create(Google2FA::class),
    HttpAuthentication::class => autowire(HttpAuthentication::class),
    ListenerProviderInterface::class => autowire(EventProvider::class),
    LoggedUserMiddleware::class => autowire(LoggedUserMiddleware::class),
    LoggerInterface::class => factory([StreamLoggerFactory::class, 'create']),
    MessageInterface::class => create(Manager::class),
    MailManagerInterface::class => autowire(MailManager::class),
    MiddlewareAclInterface::class => autowire(Acl::class),
    MiddlewareLoggerInterface::class => static function (ContainerInterface $container) {
        return new MiddlewareLogger($container->get(LoggerInterface::class));
    },
    MiddlewareFactory::class => autowire(MiddlewareFactory::class),
    MiddlewarePipeInterface::class => create(MiddlewarePipe::class),
    MiddlewareResponseInterface::class => static function (ContainerInterface $container) {
        $middleware = $container->get(AppMiddlewarePipe::class);
        $middleware->pipe($container->get(RouteMiddleware::class));
        $middleware->pipe($container->get(MiddlewareLoggerInterface::class));
        $middleware->pipe('/api', $container->get(HttpAuthentication::class)->attribute('username'));
        $middleware->pipe($container->get(SessionMiddleware::class));
        $middleware->pipe($container->get(LoggedUserMiddleware::class));
        $middleware->pipe($container->get(MiddlewareAclInterface::class));
        $middleware->pipe($container->get(FlashSessionMiddleware::class));
        $middleware->pipe($container->get(EventMiddlewareInterface::class));
        $middleware->pipe($container->get(RequestHandler::class));

        return $middleware->handle($container->get(ServerRequestInterface::class));
    },
    PdfBuilderInterface::class => create(PdfBuilder::class),
    ResponseInterface::class => create(Response::class),
    ResponseFactoryInterface::class => create(ResponseFactory::class),
    RequestHandler::class => static function (ContainerInterface $container) {
        return new RequestHandler($container);
    },
    RolesInterface::class => autowire(Roles::class),
    RunnerInterface::class => static function (ContainerInterface $container) {
        return new ErrorHandlerFactory(
            $container->get(ErrorLoggerInterface::class),
            $container->get(ConfigInterface::class)
        );
    },
    SapiEmitter::class => create(SapiEmitter::class),
    ServiceArgumentResolver::class => static function (ContainerInterface $container) {
        return new ServiceArgumentResolver($container);
    },
    SessionMiddleware::class => autowire(SessionMiddleware::class),
    SessionPersistenceInterface::class => create(PhpSessionPersistence::class),
    ServerRequestInterface::class => static function () {
        return ServerRequestFactory::fromGlobals();
    },
    ServerUrlHelper::class => create(ServerUrlHelper::class),
    TcPdf::class => create(TcPdf::class),
    TemplateRendererInterface::class => factory([TwigFactory::class, 'create']),
    TranslatorInterface::class => factory([TranslatorFactory::class, 'create']),
    TranslationInterface::class => autowire(Translation::class),
    TwigExtension::class => static function (ContainerInterface $container) {
        return new TwigExtension(
            $container->get(ServerUrlHelper::class),
            $container->get(UrlHelper::class),
            null, null
        );
    },
    TwigAssetsExtension::class => static function (ContainerInterface $container) {
        $config = $container->get(ConfigInterface::class);
        $env = $container->get(Environment::class);

        return new TwigAssetsExtension($env, (array)$config->get('templates')->get('external'));
    },
    FileManager::class => autowire(FileManager::class),
    UrlHelper::class => factory(UrlHelperFactory::class),
    UserLocaleMiddleware::class => autowire(UserLocaleMiddleware::class),
    UserLoginForm::class => autowire(UserLoginForm::class),
    UserChangePassword::class => autowire(UserChangePassword::class),
    'Zend\Expressive\Router\RouterInterface' => static function (ContainerInterface $container) {
        return new FastRouteRouter(
            null,
            $container->get(ConfigInterface::class)['routes']
        );
    },
];
