<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Helper\FileManager;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Entity\Entity\Code;
use \Vemid\ProjectOne\Entity\Entity\Supplier as EntitySupplier;
use \Vemid\ProjectOne\Entity\Entity\SupplierReceipt;
use Vemid\ProjectOne\Entity\Repository\SupplierReceiptRepository;
use Zend\Diactoros\UploadedFile;

/**
 * Class SupplierReceiptWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class SupplierReceiptWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager): array
    {
        /** @var SupplierReceiptRepository $supplierReceiptRepository */
        $supplierReceiptRepository = $entityManager->getRepository(SupplierReceipt::class);
        $supplierReceipts = $supplierReceiptRepository->fetchSupplierReceipts($this->limit, $this->offset, $this->filterColumns);
        $totalSupplierReceipts = $supplierReceiptRepository->fetchSupplierReceipts(0, 0);
        $filteredSupplierReceipts = $supplierReceiptRepository->fetchSupplierReceipts(0, 0, $this->filterColumns);

        $data = [];
        foreach ($supplierReceipts as $supplierReceipt) {
            $data[] = [
                (string)$supplierReceipt->getSupplier(),
                HtmlTag::link('/form/files/download/' . $supplierReceipt->getFile(), sprintf('Prijemnica (%s)', (string)$supplierReceipt->getSupplier()), 'text-info'),
                $supplierReceipt->getDate()->format('m.d.Y'),
                $supplierReceipt->getPaymentDate() ? $supplierReceipt->getPaymentDate()->format('m.d.Y') : '',
                HtmlTag::groupLink([
                    HtmlTag::link('/supplier-receipt-items/create/' . $supplierReceipt->getId(), false, 'text-success bigger-120', 'receipt', false),
                    HtmlTag::link('/supplier-receipts/update/' . $supplierReceipt->getId(), false, 'text-default bigger-120', 'pencil-square-o', false),
                    HtmlTag::link('#', false, 'text-danger bigger-120', 'trash-o', false, [
                        'data-delete' => '',
                        'data-form-url' => htmlspecialchars(PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', '/supplier-receipts/delete/' . $supplierReceipt->getId())),
                        'data-title' => $this->translator->_('Obriši proizvod'),
                    ]),
                ])
            ];
        }

       return [
            'draw' => $this->page,
            'recordsTotal' => count($totalSupplierReceipts),
            'recordsFiltered' => count($filteredSupplierReceipts),
            'data' => $data
        ];
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager, FileManager $uploadFile)
    {
        $supplierReceipt = new SupplierReceipt();

        $form = $formBuilder->build($supplierReceipt);
        $postData = $form->getHttpData();

        if (!$form->isValid()) {
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
        }

        $postData['supplier'] = $supplier;
        $supplierReceipt->setData($postData);

        $entityManager->persist($supplierReceipt);
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
