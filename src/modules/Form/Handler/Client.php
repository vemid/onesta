<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Client as EntityClient;

/**
 * Class Client
 * @package Vemid\ProjectOne\Form\Handler
 */
class Client extends AbstractHandler
{
    public function fetchByTerm(EntityManagerInterface $entityManager)
    {
        $queryParams = $this->request->getQueryParams();
        if (empty($queryParams['term'])) {
            return [];
        }

        $clients = $entityManager->getRepository(EntityClient::class)->fetchByTerm($queryParams['term']);
        $data = [];
        foreach ($clients as $client) {
            $data[] = [
                'label' => (string)$client,
                'id' => $client->getId(),
                'value' => (string)$client
            ];
        }

        return $data;
    }

    public function fetchById($id, EntityManagerInterface $entityManager)
    {
        $client = $entityManager->find(EntityClient::class, $id);

        return $client ? $client->toArray() : [];
    }
}
