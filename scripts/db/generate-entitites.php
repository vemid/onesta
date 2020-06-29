<?php

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use \Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Tools\EntityGenerator;

require_once __DIR__ . '/../init.php';

$return = shell_exec(sprintf('%1$s/vendor/bin/doctrine orm:convert-mapping --from-database --namespace=Vemid\\\ProjectOne\\\Entity\\\Entity\\\ --force xml %1$s/config/xml', APP_PATH));

$driver = new XmlDriver([
    APP_PATH . '/config/xml/'
]);

$excludeTables = [
    'users',
    'user_role_assignments',
    'roles',
    'codes',
    'code_types',
    'clients',
    'audit_logs',
    'suppliers',
    'app_schema_versions',
    'bank_statements',
    'bank_statement_items',
    'client_documents',
    'purchases',
    'products',
    'supplier_products',
    'purchase_items',
    'supplier_receipts',
    'supplier_receipt_items',
];

/** @var EntityManagerInterface $em */
$em = $container->get(EntityManagerInterface::class);
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$em->getConfiguration()->setMetadataDriverImpl($driver);

$cmf = new DisconnectedClassMetadataFactory();
$cmf->setEntityManager($em);
$allMetadata = [];
foreach ($cmf->getAllMetadata() as $metadata) {
    if (in_array($metadata->getTableName(), $excludeTables, false)) {
        continue;
    }

    $allMetadata[] = $metadata;
}

$generator = new EntityGenerator();
$generator->setUpdateEntityIfExists(false);
$generator->setRegenerateEntityIfExists(false);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);

$generator->generate($allMetadata, APP_PATH . '/src/models/Entity');


print 'Done!';