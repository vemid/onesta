<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Application\Cli;

require_once __DIR__ . '/../init.php';

/** @var EntityManagerInterface $entityManager */
$entityManager = $container->get(EntityManagerInterface::class);
$entityManager->getConfiguration()->setEntityNamespaces([
    'Vemid' => 'Vemid\\ProjectOne\\Entity\\',
]);

$conn = $entityManager->getConnection();
$platform = $conn->getDatabasePlatform();

$platform->registerDoctrineTypeMapping('enum', 'string');

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
