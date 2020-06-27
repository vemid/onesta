<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\SupplierReceipt;

/**
 * Class PurchaseRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class SupplierReceiptRepository extends EntityRepository
{
    use FilterTrait;

    /**
     * @param $limit
     * @param $offset
     * @param array $criteria
     * @return SupplierReceipt[]
     */
    public function fetchSupplierReceipts($limit, $offset, $criteria = [])
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('sr')
            ->from(SupplierReceipt::class, 'sr')
            ->leftJoin(SupplierReceipt::class, 's', Join::WITH, 'sr.supplier = s.id')
            ->where('1=1');

        if (\count($criteria)) {
            $this->filterCriteriaBuilder($queryBuilder, $criteria, SupplierReceipt::class);
        }

        if ($offset) {
            $queryBuilder->setFirstResult($offset);
        }

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->execute();
    }
}