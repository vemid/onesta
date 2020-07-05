<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Supplier;
use Vemid\ProjectOne\Entity\Entity\SupplierReceipt;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;

/**
 * Class PurchaseRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class SupplierReceiptItemRepository extends EntityRepository
{
    /**
     * @param Supplier $supplier
     * @param Product $product
     * @return float
     */
    public function fetchLastRetailPrice(Supplier $supplier, Product $product)
    {
        $result = $this->getEntityManager()->createQueryBuilder()
            ->select('sri.retailPrice as retailPrice')
            ->from(SupplierReceiptItem::class, 'sri')
            ->leftJoin(SupplierReceipt::class, 'sr', Join::WITH, 'sr.id = sri.supplierReceipt')
            ->where('sr.supplier = :supplier')
            ->andWhere('sri.product = :product')
            ->orderBy('sri.createdAt')
            ->setParameters([
                'supplier' => $supplier,
                'product' => $product
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

        return !empty($result) ? $result[0]['retailPrice'] : 0;
    }
}