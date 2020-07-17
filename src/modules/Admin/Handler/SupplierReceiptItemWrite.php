<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\FileManager;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Stock;
use Vemid\ProjectOne\Entity\Entity\SupplierProduct;
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
        /** @var $supplierReceipt SupplierReceipt */
        if (!$supplierReceipt = $entityManager->find(SupplierReceipt::class, $supplierReceiptId)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Dobavljač ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData = $this->request->getParsedBody();

        if (!isset($postData['postData'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Podaci ne postoji!'), null, Builder::DANGER);
            return;
        }

        $errorMessages = [];
        foreach ($postData['postData'] as $row => &$data) {
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
            foreach ($postData['postData'] as $row => &$data) {

                $supplier = $supplierReceipt->getSupplier();
                $supplierProduct = $entityManager->getRepository(SupplierProduct::class)->findOneBy([
                    'product' => $data['product'],
                    'supplier' => $supplier
                ]);

                if (!$supplierProduct) {
                    $supplierProduct = new SupplierProduct();
                    $supplierProduct->setProduct($data['product']);
                    $supplierProduct->setSupplier($supplier);
                }

                $avgPrice = $entityManager->getRepository(EntityProduct::class)
                    ->fetchProductAveragePurchasePriceBySupplier($data['product'], $supplier, $data['price'], $data['qty']);

                $avgPrice = $avgPrice > 0  ? $avgPrice : $data['price'];

                $supplierProduct->setAvgPurchasePrice($avgPrice);
                $supplierProduct->setRetailPrice($data['retailPrice']);
                $entityManager->persist($supplierProduct);

                $supplierReceiptItem = new SupplierReceiptItem();
                $supplierReceiptItem->setData($data);
                $supplierReceiptItem->setSupplierProduct($supplierProduct);
                $supplierReceiptItem->setType(Stock::INCOME);
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

        $supplier = $supplierReceipt->getSupplier();
        $supplierProduct = $entityManager->getRepository(SupplierProduct::class)->findOneBy([
            'product' => $product,
            'supplier' => $supplier
        ]);

        if (!$supplierProduct) {
            $this->messageBag->pushFlashMessage($this->translator->_('Dobavljač ne postoji!'), null, Builder::DANGER);
            return;
        }

        $avgPrice = $entityManager->getRepository(Product::class)
            ->fetchProductAveragePurchasePriceBySupplier($product, $supplier);

        $supplierProduct->setAvgPurchasePrice($avgPrice);
        $supplierProduct->setRetailPrice($supplierReceiptItem->getRetailPrice());
        $entityManager->persist($supplierProduct);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Record updated'), null, Builder::SUCCESS);

        $this->redirect('/supplier-receipts/overview/' . $supplierReceipt->getId());
    }
}
