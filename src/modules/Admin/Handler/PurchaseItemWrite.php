<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use \Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\SupplierProduct;

/**
 * Class PurchaseItemWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PurchaseItemWrite extends GridHandler
{
    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $purchaseItem PurchaseItem */
        if (!$purchaseItem = $entityManager->find(PurchaseItem::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nesto nije u redu. Ne postoji traženi dobavljač'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($purchaseItem);
        $postData = $form->getHttpData();

        /** @var $supplierProduct SupplierProduct */
        if (!$supplierProduct = $entityManager->find(SupplierProduct::class, (int)$postData['supplierProduct'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nesto nije u redu. Ne postoji traženi proizvod'), null, Builder::WARNING);
            return;
        }

        $postData['supplierProduct'] = $supplierProduct;

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $purchaseItem->setData($postData);

        $entityManager->persist($purchaseItem);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Dobaljač izmenjen'), null, Builder::SUCCESS);

        $this->redirect('/purchases/add-items/' . $purchaseItem->getPurchase()->getId());
    }
}
