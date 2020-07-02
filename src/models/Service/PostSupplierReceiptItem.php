<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Service;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Entity\Entity\Product as EntityProduct;
use Vemid\ProjectOne\Entity\Entity\Stock;
use Vemid\ProjectOne\Entity\Entity\SupplierProduct;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;

/**
 * Class PostSupplierReceiptItem
 * @package Vemid\ProjectOne\Entity\Service
 */
class PostSupplierReceiptItem
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * PostSupplierReceiptItem constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param SupplierReceiptItem $supplierReceiptItem
     */
    public function setProductSuplierAndStockQty(SupplierReceiptItem $supplierReceiptItem)
    {
        if (!$supplier = $supplierReceiptItem->getSupplierReceipt()->getSupplier()) {
            throw new \LogicException('Supplier do not exist!');
        }

        $supplierProduct = $this->entityManager->getRepository(SupplierProduct::class)->findOneBy([
            'product' => $supplierReceiptItem->getProduct(),
            'supplier' => $supplier
        ]);

        if (!$supplierProduct) {
            $supplierProduct = new SupplierProduct();
            $supplierProduct->setProduct($supplierReceiptItem->getProduct());
            $supplierProduct->setSupplier($supplier);
        }

        $avgPrice = $this->entityManager->getRepository(EntityProduct::class)
            ->fetchProductAveragePurchasePriceBySupplier($supplierReceiptItem->getProduct(), $supplier);


        $supplierProduct->setAvgPurchasePrice($avgPrice);
        $supplierProduct->setRetailPrice($supplierReceiptItem->getRetailPrice());
        $this->entityManager->persist($supplierProduct);

        $stock = new Stock();
        $stock->setSupplierProduct($supplierProduct);
        $stock->setPurchasePrice($supplierReceiptItem->getPrice());
        $stock->setQty($supplierReceiptItem->getQty());
        $stock->setType(Stock::INCOME);
        $this->entityManager->persist($stock);

        $this->entityManager->flush();
    }

    /**
     * @param SupplierReceiptItem $supplierReceiptItem
     */
    public function removeProductSupplier(SupplierReceiptItem $supplierReceiptItem)
    {
        if (!$supplier = $supplierReceiptItem->getSupplierReceipt()->getSupplier()) {
            throw new \LogicException('Supplier do not exist!');
        }

        /** @var SupplierProduct $supplierProduct */
        $supplierProduct = $this->entityManager->getRepository(SupplierProduct::class)->findOneBy([
            'product' => $supplierReceiptItem->getProduct(),
            'supplier' => $supplier
        ]);

        if (!$supplierProduct) {
            throw new \LogicException('SupplierProduct not found!');
        }

        foreach ($supplierProduct->getStocks() as $stock) {
            $this->entityManager->remove($stock);
        }

        $this->entityManager->remove($supplierProduct);
        $this->entityManager->flush();
    }
}
