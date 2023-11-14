<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MongoLogger;
use Psr\Log\LoggerInterface;
use \Exception;

/**
 * Class StreamLoggerFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class StreamLoggerFactory implements StreamLoggerInterface
{
    /**
     * @throws \Exception
     */
    public function create(ConfigInterface $config): LoggerInterface
    {
        return $this($config);
    }

    /**
     * @throws Exception
     */
    public function __invoke(ConfigInterface $config): LoggerInterface
    {
        $stOutStreamHandler = new StreamHandler('php://stdout');
        $stOutStreamHandler->setFormatter(new JsonFormatter());

        $logger = new MongoLogger('logs');
        $logger->pushHandler($stOutStreamHandler);

        return $logger;
    }
}
