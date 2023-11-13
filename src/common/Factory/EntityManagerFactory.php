<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use DoctrineExtensions\Query\Mysql\Date;
use DoctrineExtensions\Query\Mysql\Sha1;
use Vemid\ProjectOne\Common\Config\ConfigInterface;

/**
 * Class EntityManagerFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class EntityManagerFactory
{
    private Connection $connection;

    private ConfigInterface $config;

    private EventManager $eventManager;

    public function __construct(
        Connection $connection,
        ConfigInterface $config,
        EventManager $eventManager
    ) {
        $this->connection = $connection;
        $this->config = $config;
        $this->eventManager = $eventManager;
    }

    /**
     * @throws MissingMappingDriverImplementation
     */
    public function create(): EntityManager
    {
        $config = ORMSetup::createXMLMetadataConfiguration(
            [APP_PATH . '/config/xml'],
            (bool)$this->config->get('db')->get('debug'),
            sprintf('%s/var/cache/doctrine', APP_PATH)
        );

        $config->addCustomStringFunction('sha1', Sha1::class);
        $config->addCustomStringFunction('DATE', Date::class);
        $config->addCustomStringFunction('CURDATE', CurrentDateFunction::class);

        return new EntityManager($this->connection, $config, $this->eventManager);
    }
}
