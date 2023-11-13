<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Doctrine\ORM\Tools\Setup;
use DoctrineExtensions\Query\Mysql\Date;
use DoctrineExtensions\Query\Mysql\Sha1;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use \Doctrine\ORM\Mapping\Driver\XmlDriver;

/**
 * Class EntityManagerFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class EntityManagerFactory
{
    /** @var Connection */
    private $connection;

    /** @var ConfigInterface */
    private $config;

    /** @var EventManager */
    private $eventManager;

    /**
     * EntityManagerFactory constructor.
     * @param Connection $connection
     * @param ConfigInterface $config
     * @param EventManager $eventManager
     */
    public function __construct(Connection $connection, ConfigInterface $config, EventManager $eventManager)
    {
        $this->connection = $connection;
        $this->config = $config;
        $this->eventManager = $eventManager;
    }

    /**
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
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

        return EntityManager::create($this->connection, $config, $this->eventManager);
    }
}
