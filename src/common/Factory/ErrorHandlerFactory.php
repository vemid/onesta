<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Phoundation\ErrorHandling\RunnerInterface;
use Psr\Log\LoggerInterface;
use Vemid\ProjectOne\Common\ErrorHandling\Whoops\CommandLineFormatter;
use Vemid\ProjectOne\Common\ErrorHandling\Whoops\HandlerChain;
use Vemid\ProjectOne\Common\ErrorHandling\Whoops\NullHandler;
use Vemid\ProjectOne\Common\ErrorHandling\Whoops\SetHttpStatusCodeHandler;
use Vemid\ProjectOne\Common\ErrorHandling\Whoops\SuppressErrorDetailsHandler;
use Vemid\ProjectOne\Common\ErrorHandling\Whoops\WhoopsRunner;
use Vemid\ProjectOne\Common\ErrorHandling\LogHandler;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler as WhoopsJsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;
use Whoops\Util\Misc as WhoopsUtil;

/**
 * Class HandlerFactory
 * @package Library\Error
 */
class ErrorHandlerFactory
{
    private LoggerInterface $logger;

    private ConfigInterface $config;

    /**
     * ErrorHandlerFactory constructor.
     */
    public function __construct(LoggerInterface $logger, ConfigInterface $config)
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    public function __invoke(): RunnerInterface
    {
        $displayErrors = (bool)$this->config->get('error_handling')->get('display_errors');

        $whoops = new Whoops();
        $runner = new WhoopsRunner($whoops);

        $whoops->pushHandler(new HandlerChain(
            new CallbackHandler(new LogHandler($this->logger)),
            new SetHttpStatusCodeHandler(),
            (! $displayErrors) ? new SuppressErrorDetailsHandler() : new NullHandler(),
            $this->determineResponseHandler($displayErrors)
        ));

        return $runner;
    }

    /**
     * @param bool $displayErrors
     * @return Handler
     */
    private function determineResponseHandler(bool $displayErrors): Handler
    {
        if (WhoopsUtil::isAjaxRequest() || (isset($_SERVER['HTTP_ACCEPT']) && false !== stripos($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
            return new WhoopsJsonResponseHandler();
        }

        if (WhoopsUtil::isCommandLine()) {
            return new CommandLineFormatter();
        }

        if ($displayErrors) {
            return new PrettyPageHandler();
        }

        return new PlainTextHandler();
    }
}
