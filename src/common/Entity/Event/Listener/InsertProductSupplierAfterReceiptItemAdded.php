<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Entity\Event\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;
use Vemid\ProjectOne\Entity\Service\PostSupplierReceiptItem;

/**
 * Class InsertProductSupplierAfterReceiptItemAdded
 * @package Vemid\ProjectOne\Common\Entity\Event\Listener
 */
class InsertProductSupplierAfterReceiptItemAdded
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof SupplierReceiptItem) {
            $supplierHelper = new PostSupplierReceiptItem($entityManager);
            $supplierHelper->setProductSuplierAndStockQty($entity);
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof SupplierReceiptItem) {
            $supplierHelper = new PostSupplierReceiptItem($entityManager);
            $supplierHelper->removeProductSupplier($entity);
        }
    }
}
