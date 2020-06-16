<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\Common\Collections\Criteria;
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

    /**
     * @param $limit
     * @param $offset
     * @param array $criteria
     * @return Product[]
     */
    public function fetchProducts($limit, $offset, $criteria = [])
    {
        $metadataProduct = $this->getEntityManager()->getClassMetadata(Product::class);
        $productProperties = preg_filter('/^/', 'p.', $metadataProduct->getFieldNames());

        $metadataCode = $this->getEntityManager()->getClassMetadata(Code::class);
        $codeProperties = preg_filter('/^/', 'c.', $metadataCode->getFieldNames());
        $properties = array_merge($productProperties, $codeProperties);

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->leftJoin(Code::class, 'c', Join::WITH, 'p.code = c.id')
            ->where('1=1');

        if (\count($criteria)) {
            $params = [];
            $counter = 1;

            foreach ($criteria as $field => $value) {
                $number = ctype_digit($value);

                if ($field !== '*') {
                    $queryBuilder->andWhere(sprintf('%s %s :param%s', $field, $number ? '=' : 'LIKE', $counter));
                    $params["param$counter"] = sprintf('%1$s%2$s%1$s', !$number ? '%' : '', $value);

                    $counter++;
                } else {

                    $criteria = [];
                    foreach ($properties as $property) {
                        $exp = $number ? 'eq' : 'like';
                        $criteria[] = $queryBuilder->expr()->{$exp}($property, sprintf('\'%1$s%2$s%1$s\'', !$number ? '%' : '', $value));
                    }

                    $queryBuilder->andWhere($queryBuilder->expr()->orX(...$criteria));
                }
            }

            $queryBuilder->setParameters($params);
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
