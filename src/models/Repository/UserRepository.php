<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class UserRepository extends EntityRepository
{
    public function getAllAdminUsers($all)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT * FROM User'
            )
            ->getResult();
    }
}
