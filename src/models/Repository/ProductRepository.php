<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Supplier;
use Vemid\ProjectOne\Entity\Entity\SupplierReceipt;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;

/**
 * Class ProductRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class ProductRepository extends EntityRepository
{
    use FilterTrait;

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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->leftJoin(Code::class, 'c', Join::WITH, 'p.code = c.id')
            ->where('1=1');

        if (\count($criteria)) {
           $this->filterCriteriaBuilder($queryBuilder, $criteria, Product::class);
        }

        if ($offset) {
            $queryBuilder->setFirstResult($offset);
        }

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->execute();
    }

    public function fetchProductAveragePurchasePriceBySupplier(Product $product, Supplier $supplier)
    {
        $avgPriceResult = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(sri.price * sri.qty) / SUM(sri.qty) as avgPrice')
            ->from(Product::class, 'p')
            ->leftJoin(SupplierReceiptItem::class, 'sri', Join::WITH, 'p.id = sri.product')
            ->leftJoin(SupplierReceipt::class, 'sr', Join::WITH, 'sr.id = sri.supplierReceipt')
            ->where('p.id = :product')
            ->andWhere('sr.supplier = :supplier')
            ->setParameters([
                'product' => $product->getId(),
                'supplier' => $supplier->getId()
            ])
            ->getQuery()
            ->execute();

        return (float)$avgPriceResult[0]['avgPrice'];
    }
}
