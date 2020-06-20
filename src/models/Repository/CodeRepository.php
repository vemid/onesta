<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\CodeType;

/**
 * Class SupplierRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class CodeRepository extends EntityRepository
{
    use FilterTrait;

    /**
     * @param $limit
     * @param $offset
     * @param array $criteria
     * @return Code[]
     */
    public function fetchCodes($limit, $offset, $criteria = [])
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from(Code::class, 'c')
            ->leftJoin(CodeType::class, 'ct', Join::WITH, 'c.codeType = ct.id')
            ->where('1=1');

        if (\count($criteria)) {
            $this->filterCriteriaBuilder($queryBuilder, $criteria, Code::class);
        }

        if ($offset) {
            $queryBuilder->setFirstResult($offset);
        }

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @return Code[]
     */
    public function findParentByUniqueCode()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('c2')
            ->from(Code::class, 'c')
            ->leftJoin(Code::class, 'c2', Join::WITH, 'c2.id = c.parent')
            ->where('c.parent IS NOT NULL')
            ->groupBy('c2.id')
            ->getQuery()
            ->execute();
    }

    /**
     * @return CodeType[]
     */
    public function findByUniqueCodeType()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('ct')
            ->from(Code::class, 'c')
            ->leftJoin(CodeType::class, 'ct', Join::WITH, 'c.codeType = ct.id')
            ->groupBy('ct.code')
            ->getQuery()
            ->execute();
    }
}
