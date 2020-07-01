<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\FileManager;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\SupplierReceipt;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;

/**
 * Class SupplierReceiptWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class SupplierReceiptItemWrite extends GridHandler
{

    public function create($supplierReceiptId, FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        if (!$supplierReceipt = $entityManager->find(SupplierReceipt::class, $supplierReceiptId)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Dobavljač ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData = $this->request->getParsedBody();

        if (!isset($postData['supplierReceiptItem'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Podaci ne postoji!'), null, Builder::DANGER);
            return;
        }

        $errorMessages = [];
        foreach ($postData['supplierReceiptItem'] as $row => &$data) {
            $supplierReceiptItem = new SupplierReceiptItem();
            $form = $formBuilder->build($supplierReceiptItem, [], false);
            $form->setValues($data, true);

            $form->validate();
            foreach ($form->getControls() as $control) {
                foreach ($control->getErrors() as $error) {
                    $errorMessages[$row] = ['field' => $control->getName(), 'message' => $error];
                }
            }

            if (!$product = $entityManager->find(Product::class, $data['product'])) {
                $errorMessages[$row] = ['field' => 'product', 'message' => $this->translator->_('Proizvod ne postoji!')];
            } else {
                $data['supplierReceipt'] = $supplierReceipt;
                $data['product'] = $product;
            }
        }

        if (count($errorMessages) === 0) {
            foreach ($postData['supplierReceiptItem'] as $row => &$data) {
                $supplierReceiptItem = new SupplierReceiptItem();
                $supplierReceiptItem->setData($data);
                $entityManager->persist($supplierReceiptItem);
            }

            $entityManager->flush();
            $this->messageBag->pushFlashMessage($this->translator->_('Prijemnice dodate!'), null, Builder::SUCCESS);

            return ['error' => false];
        } else {
            $this->messageBag->pushFlashMessage($this->translator->_('Greška u podacima!'), null, Builder::DANGER);

            return ['error' => true, 'rowMessages' => $errorMessages];
        }

        return $this->redirect('/supplier-receipts/overview/' . $supplierReceiptId);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder, FileManager $uploadFile)
    {
        /** @var $supplierReceiptItem SupplierReceiptItem */
        if (!$supplierReceiptItem = $entityManager->find(SupplierReceiptItem::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, stavka prijemnice ne postoji'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($supplierReceiptItem);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$supplierReceipt = $entityManager->find(SupplierReceipt::class, $postData['supplierReceipt'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Prijemnica ne postoji!'), null, Builder::DANGER);
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

        $this->messageBag->pushFlashMessage($this->translator->_('Record updated'), null, Builder::SUCCESS);

        $this->redirect('/supplier-receipts/overview/' . $supplierReceipt->getId());
    }
}
