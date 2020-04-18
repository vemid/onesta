<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\Common\EventManager;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use \Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;

/**
 * Class ConnectionFactory
 * @package Library\Db
 */
class ConnectionFactory
{
    /**
     * @param ConfigInterface $config
     * @param LoggerInterface $logger
     * @param EventManager $eventManager
     * @return Connection
     * @throws DBALException
     */
    public function create(ConfigInterface $config, LoggerInterface $logger, EventManager $eventManager): Connection
    {
        return $this($config, $logger, $eventManager);
    }

    /**
     * @param ConfigInterface $config
     * @param LoggerInterface $logger
     * @param EventManager $eventManager
     * @return Connection
     * @throws DBALException
     */
    public function __invoke(ConfigInterface $config, LoggerInterface $logger, EventManager $eventManager): Connection
    {
        $connectionParams = [
            'dbname' => $config->get('db')->get('name'),
            'user' => $config->get('db')->get('username'),
            'password' => $config->get('db')->get('password'),
            'host' => $config->get('db')->get('host'),
            'port' => $config->get('db')->get('port'),
            'driver' => 'pdo_mysql',
        ];

        return DriverManager::getConnection($connectionParams, new Configuration(), $eventManager);
    }
}
