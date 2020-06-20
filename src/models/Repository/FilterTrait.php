<?php


namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Supplier;

/**
 * Trait FilterTrait
 * @package Vemid\ProjectOne\Entity\Repository
 */
trait FilterTrait
{
    public function filterCriteriaBuilder(QueryBuilder $queryBuilder, array $criteria, string $className)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();
        $metadata = $entityManager->getClassMetadata($className);
        $prefix = strtolower(@end((explode('\\', $className)))[0]);
        $classProperties = preg_filter('/^/', $prefix .'.', $metadata->getFieldNames());
        $relationProperties = $metadata->getAssociationNames();

        $properties = [];
        foreach ($relationProperties as $relationProperty) {
            $relatedClass = $metadata->getAssociationTargetClass($relationProperty);
            $relatedMetadata = $entityManager->getClassMetadata($relatedClass);
            $className = @end((explode('\\', $relatedClass)));
            $relatedAlias = strtolower($className[0]);

            if (preg_match_all('/((?:^|[A-Z])[a-z]+)/', $className, $matches)) {
                $camelCased = $matches[0];
                array_shift($camelCased);

                foreach ($camelCased as $match) {
                    $relatedAlias .=  strtolower($match[0]);
                }
            }

            $relatedProperties = preg_filter('/^/', $relatedAlias .'.', ($relatedAlias !== $prefix ? $relatedMetadata->getFieldNames(): [$relationProperty]));
            $properties[] = array_merge($classProperties, $relatedProperties);
        }

        if (count($properties)) {
            $properties = array_unique(array_merge(...$properties));
        } else {
            $properties = $classProperties;
        }

        $params = [];
        $counter = 1;
        foreach ($criteria as $field => $value) {
            if ($field !== '*') {
                $propertyField = explode('.', $field)[1];

                $number = ctype_digit($value) && in_array($propertyField, $relationProperties, false);

                $queryBuilder->andWhere(sprintf('%s %s :param%s', $field, $number ? '=' : 'LIKE', $counter));
                $params["param$counter"] = addcslashes(sprintf('%1$s%2$s%1$s', !$number ? '%' : '', $value), '\n');

                $counter++;
            } else {

                $criteria = [];
                foreach ($properties as $property) {
                    $propertyCode = explode('.', $property)[1];
                    $number = in_array($propertyCode, $relationProperties, false);
                    if ($number) {
                        continue;
                    }

                    $exp = 'like';
                    $criteria[] = $queryBuilder->expr()->{$exp}($property, sprintf('\'%1$s%2$s%1$s\'', !$number ? '%' : '', $value));
                }

                $queryBuilder->andWhere($queryBuilder->expr()->orX(...$criteria));
            }
        }

        $queryBuilder->setParameters($params);
    }
}