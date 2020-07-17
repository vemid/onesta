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

        $form = $formBuilder->build(new EntitySupplierReceiptItem());
        $form->getComponent('supplierReceipt')->setDefaultValue($supplierReceipt->getId());

        $this->view->setTemplate('supplier-receipt-item::create.html.twig', [
            'form' => $form
        ]);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $supplierReceipt EntitySupplierReceiptItem */
        if (!$supplierReceiptItem = $entityManager->find(EntitySupplierReceiptItem::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena prijemnica'), null, Builder::WARNING);
        }

        $form = $formBuilder->build($supplierReceiptItem);
        $form->getComponent('product')->setAttribute('readonly', true);
        $this->view->setTemplate('supplier-receipt::update.html.twig', [
            'form' => $form,
        ]);
    }
}
