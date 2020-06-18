<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Supplier;

/**
 * Class SupplierRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class SupplierRepository extends EntityRepository
{
    use FilterTrait;

    /**
     * @param $limit
     * @param $offset
     * @param array $criteria
     * @return Supplier[]
     */
    public function fetchSuppliers($limit, $offset, $criteria = [])
    {
        $metadataProduct = $this->getEntityManager()->getClassMetadata(Supplier::class);
        $productProperties = preg_filter('/^/', 'p.', $metadataProduct->getFieldNames());

        $metadataCode = $this->getEntityManager()->getClassMetadata(Code::class);
        $codeProperties = preg_filter('/^/', 'c.', $metadataCode->getFieldNames());
        $properties = array_merge($productProperties, $codeProperties);

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from(Supplier::class, 's')
            ->where('1=1');

        if (\count($criteria)) {
            $this->filterCriteriaBuilder($queryBuilder, $criteria, Supplier::class);
        }

        if ($offset) {
            $queryBuilder
                ->setFirstResult($offset);
        }

        if ($limit) {
            $queryBuilder
                ->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->execute();
    }
}
