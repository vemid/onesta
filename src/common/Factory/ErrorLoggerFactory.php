<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Sentry\ClientBuilder;
use Sentry\Monolog\Handler;
use Sentry\State\Hub;
use \Monolog\Logger as MongoLogger;
use \Exception;

/**
 * Class Logger
 * @package Library\Factory
 */
class ErrorLoggerFactory implements ErrorLoggerInterface
{
    /**
     * @param ConfigInterface $config
     * @return LoggerInterface
     * @throws Exception
     */
    public function create(ConfigInterface $config): LoggerInterface
    {
        return $this($config);
    }

    /**
     * @param ConfigInterface $config
     * @return LoggerInterface
     * @throws Exception
     */
    public function __invoke(ConfigInterface $config): LoggerInterface
    {
//        $client = ClientBuilder::create([
//            'dsn' => $config->get('sentry.dns')
//        ])->getClient();

        $stdErrStreamHandler = new StreamHandler('php://stderr');
        $stdErrStreamHandler->setFormatter(new JsonFormatter());

        $fileStreamHandler = new StreamHandler(sprintf('%s/var/logs/error.log', APP_PATH));
        $fileStreamHandler->setFormatter(new JsonFormatter());

        $logger = new MongoLogger('error-logs');
        $logger->pushHandler($fileStreamHandler);
        $logger->pushHandler($stdErrStreamHandler);
//        $logger->pushHandler(new Handler(new Hub($client)));

        return $logger;
    }
}
