<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\SupplierReceipt;
use \Vemid\ProjectOne\Entity\Entity\SupplierReceipt as EntitySupplierReceipt;
use \Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem as EntitySupplierReceiptItem;

/**
 * Class Product
 * @package Vemid\ProjectOne\Admin\Handler
 */
class SupplierReceiptItem extends AbstractHandler
{
    public function create($supplierId, FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager): void
    {
        /** @var $supplierReceipt SupplierReceipt */
        if (!$supplierReceipt = $entityManager->find(EntitySupplierReceipt::class, (int)$supplierId)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, prijemnica ne postoji'), null, Builder::WARNING);
            return;
        }

        $this->view->setTemplate('supplier-receipt-item::create.html.twig', [
            'form' => $formBuilder->build(new EntitySupplierReceiptItem())
        ]);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $supplierReceipt EntitySupplierReceipt */
        if (!$supplierReceipt = $entityManager->find(EntitySupplierReceipt::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena prijemnica'), null, Builder::WARNING);
        }

        $this->view->setTemplate('supplier-receipt::update.html.twig', [
            'form' => $formBuilder->build($supplierReceipt),
            'file' => $supplierReceipt->getFile()
        ]);
    }
}
