<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use Vemid\ProjectOne\Entity\Entity\User;
use Vemid\ProjectOne\Entity\Entity\UserRoleAssignment;

require_once __DIR__ . '/../init.php';

$entityManager = $container->get(EntityManagerInterface::class);

$storage = new StorageClient([
    'keyFile' => json_decode(file_get_contents(sprintf('%s/config/google.json', APP_PATH)), true)
]);

$bucket = $storage->bucket('dbs');

// Upload a file to the bucket.
$storeObject = $bucket->upload(
    fopen('test.db', 'r')
);

$test = 0;