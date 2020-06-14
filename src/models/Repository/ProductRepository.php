<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Product;

/**
 * Class ProductRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class ProductRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findByUniqueCode()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from(Product::class, 'p')
            ->leftJoin(Code::class, 'c', Join::WITH, 'p.code = c.id')
            ->groupBy('p.code')
            ->getQuery()
            ->execute();
    }
}