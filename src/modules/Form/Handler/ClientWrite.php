<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Client as EntityClient;

/**
 * Class ClientWrite
 * @package Vemid\ProjectOne\Form\Handler
 */
class ClientWrite extends AbstractHandler
{
    public function fetchByTerm(EntityManagerInterface $entityManager)
    {
        $queryParams = $this->request->getParsedBody();
        if (empty($queryParams['term'])) {
            return [];
        }

        $clients = $entityManager->getRepository(EntityClient::class)->fetchByTerm($queryParams['term']);
        $data = [];
        foreach ($clients as $client) {
            if (!empty($queryParams['clientId']) && (int)$queryParams['clientId'] === $client->getId()) {
                continue;
            }

            $data[] = [
                'label' => (string)$client,
                'id' => $client->getId(),
                'value' => (string)$client
            ];
        }

        return $data;
    }
}
