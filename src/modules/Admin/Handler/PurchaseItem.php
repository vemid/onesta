<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\SupplierReceiptFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\SupplierReceipt as EntitySupplierReceipt;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem as EntitySupplierReceiptItem;

/**
 * Class PurchaseItem
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PurchaseItem extends AbstractHandler
{
    public function list(SupplierReceiptFilterForm $supplierReceiptFilterForm): void
    {
        $this->view->setTemplate('supplier-receipt::list.html.twig', [
            'form' => $supplierReceiptFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder): void
    {
        $this->view->setTemplate('supplier-receipt::create.html.twig', [
            'form' => $formBuilder->build(new EntitySupplierReceipt())
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

    public function overview($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $supplierReceipt EntitySupplierReceipt */
        if (!$supplierReceipt = $entityManager->find(EntitySupplierReceipt::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena prijemnica'), null, Builder::WARNING);
        }

        $form = $formBuilder->build(new EntitySupplierReceiptItem());
        $form->getComponent('supplierReceipt')->setDefaultValue($supplierReceipt->getId());
        $componentProduct = $form->getComponent('product');
        $items = $componentProduct->getItems();

        foreach ($supplierReceipt->getSupplierReceiptItems() as $supplierReceiptItem) {
            if (isset($items[$supplierReceiptItem->getProduct()->getId()])) {
                unset($items[$supplierReceiptItem->getProduct()->getId()]);
            }
        }

        $componentProduct->setItems($items);

        $this->view->setTemplate('supplier-receipt::overview.html.twig', [
            'supplierReceipt' => $supplierReceipt,
            'form' => $form
        ]);
    }
}
