<?php

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use \Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Tools\EntityGenerator;

require_once __DIR__ . '/../init.php';

$return = shell_exec(sprintf('%1$s/vendor/bin/doctrine orm:convert-mapping --from-database --namespace=Vemid --force xml %1$s/config/xml', APP_PATH));

$driver = new XmlDriver([
    APP_PATH . '/config/xml/'
]);
$driver->setGlobalBasename('Vemid\test');

/** @var EntityManagerInterface $em */
$em = $container->get(EntityManagerInterface::class);

$cmf = new DisconnectedClassMetadataFactory();
$cmf->setEntityManager($em);
$cmf->setCacheDriver(new ApcuCache());

$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$em->getConfiguration()->setMetadataDriverImpl($driver);
$em->getConfiguration()->addEntityNamespace('\\Vemid\\ProjectOne\\Entity', 'Vemid');

$em->getConfiguration()->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$em->getConfiguration()->setProxyDir(__DIR__ . '/Proxies');
$em->getConfiguration()->setProxyNamespace('Proxies');

$driver = new DatabaseDriver(
    $em->getConnection()->getSchemaManager()
);
$em->getConfiguration()->setMetadataDriverImpl($driver);

$generator = new EntityGenerator();
$generator->setUpdateEntityIfExists(false);
$generator->setRegenerateEntityIfExists(false);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);

$generator->generate($cmf->getAllMetadata(), APP_PATH . '/src/models/Entity');


print 'Done!';