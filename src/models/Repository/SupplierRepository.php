<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Supplier;

/**
 * Class SupplierRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class SupplierRepository extends EntityRepository
{
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

        $relationProperties = $metadataProduct->getAssociationNames();

        $metadataCode = $this->getEntityManager()->getClassMetadata(Code::class);
        $codeProperties = preg_filter('/^/', 'c.', $metadataCode->getFieldNames());
        $properties = array_merge($productProperties, $codeProperties);

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from(Supplier::class, 's')
            ->where('1=1');

        if (\count($criteria)) {
            $params = [];
            $counter = 1;

            foreach ($criteria as $field => $value) {
                $relationField = explode('.', $field)[1];
                $number = ctype_digit($value) && in_array($relationField, $relationProperties, false);

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
