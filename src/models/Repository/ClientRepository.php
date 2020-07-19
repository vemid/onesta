<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Vemid\ProjectOne\Entity\Entity\Client;

/**
 * Class ClientRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class ClientRepository extends EntityRepository
{
    /**
     * @param $term
     * @return Client[]
     */
    public function fetchByTerm($term)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from(Client::class, 'c')
            ->where('c.firstName LIKE :param')
            ->orWhere('c.lastName LIKE :param')
            ->setParameters([
                'param' => "%$term%"
            ])
            ->getQuery()
            ->execute();
    }
}