<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Exception;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Class ConnectionFactory
 * @package Library\Db
 */
class ConnectionFactory
{
    /**
     * @throws Exception
     */
    public function create(ConfigInterface $config, EventManager $eventManager): Connection
    {
        return $this($config, $eventManager);
    }

    /**
     * @throws Exception
     */
    public function __invoke(ConfigInterface $config, EventManager $eventManager): Connection
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
