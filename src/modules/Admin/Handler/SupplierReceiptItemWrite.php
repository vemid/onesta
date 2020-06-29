<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\FileManager;
use Vemid\ProjectOne\Common\Message\Builder;
use \Vemid\ProjectOne\Entity\Entity\SupplierReceipt;
use \Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;
use \Vemid\ProjectOne\Entity\Entity\Product;

/**
 * Class SupplierReceiptWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class SupplierReceiptItemWrite extends GridHandler
{

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $supplierReceiptItem = new SupplierReceiptItem();

        $form = $formBuilder->build($supplierReceiptItem);
        $postData = $form->getHttpData();

        if (!$form->isValid()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$supplierReceipt = $entityManager->find(SupplierReceipt::class, $postData['supplierReceipt'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Dobavljač ne postoji!'), null, Builder::DANGER);
            return;
        }

        if (!$product = $entityManager->find(Product::class, $postData['product'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Proizvod ne postoji!'), null, Builder::DANGER);
            return;
        }


        $postData['supplierReceipt'] = $supplierReceipt;
        $postData['product'] = $product;
        $supplierReceiptItem->setData($postData);

        $entityManager->persist($supplierReceiptItem);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Prijemnica dodata!'), null, Builder::SUCCESS);

        return $this->redirect('/supplier-receipts/list');
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder, FileManager $uploadFile)
    {
        /** @var $supplierReceipt SupplierReceipt */
        if (!$supplierReceipt = $entityManager->find(SupplierReceipt::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, prijemnica ne postoji'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($supplierReceipt);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$supplier = $entityManager->find(EntitySupplier::class, $postData['supplier'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Dobavljač ne postoji!'), null, Builder::DANGER);
            return;
        }

        $uploadedFiles = $this->request->getUploadedFiles();
        $uploadedFile = array_pop($uploadedFiles);
        if ($uploadedFile->getSize()) {
            $postData['file'] = $uploadFile->uploadFile($uploadedFile);
        } else {
            $postData['file'] = $supplierReceipt->getFile();
        }

        $postData['supplier'] = $supplier;
        $supplierReceipt->setData($postData);

        $entityManager->persist($supplierReceipt);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Record updated'), null, Builder::SUCCESS);

        $this->redirect('/supplier-receipts/list/' . $supplierReceipt->getId());
    }
}
