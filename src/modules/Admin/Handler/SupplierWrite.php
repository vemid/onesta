<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use \Vemid\ProjectOne\Entity\Entity\Supplier;
use Vemid\ProjectOne\Entity\Repository\SupplierRepository;

/**
 * Class SupplierWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class SupplierWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager): array
    {
        /** @var SupplierRepository $supplierRepository */
        $supplierRepository = $entityManager->getRepository(Supplier::class);
        $suppliers = $supplierRepository->fetchSuppliers($this->limit, $this->offset, $this->filterColumns);
        $totalSuppliers = $supplierRepository->fetchSuppliers(0, 0);
        $filteredSuppliers = $supplierRepository->fetchSuppliers(0, 0, $this->filterColumns);

        $data = [];
        foreach ($suppliers as $supplier) {
            $data[] = [
                (string)$supplier->getName(),
                $supplier->getEmail(),
                $supplier->getPhoneNumber(),
                $supplier->getAddress(),
                $supplier->getPostalCode(),
                HtmlTag::groupLink([
                    HtmlTag::link('/suppliers/overview/' . $supplier->getId(), false, 'text-success bigger-120', 'search', false),
                    HtmlTag::link('/suppliers/update/' . $supplier->getId(), false, 'text-default bigger-120', 'pencil-square-o', false),
                    HtmlTag::link('#', false, 'text-danger bigger-120', 'trash-o', false, [
                        'data-delete' => '',
                        'data-form-url' => htmlspecialchars(PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', '/suppliers/delete/' . $supplier->getId())),
                        'data-title' => $this->translator->_('Obriši dobavljača'),
                    ]),
                ])
            ];
        }

       return [
            'draw' => $this->page,
            'recordsTotal' => count($totalSuppliers),
            'recordsFiltered' => count($filteredSuppliers),
            'data' => $data
        ];
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $supplier = new Supplier();

        $form = $formBuilder->build($supplier);
        $form->setValues($_POST);

        $postData = $form->getHttpData();

        if (!$form->isValid()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $supplier->setData($postData);

//        $entityManager->persist($supplier);
//        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Dobavljač dodat!'), null, Builder::SUCCESS);

        return $this->redirect('/suppliers/list');
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $supplier Supplier */
        if (!$supplier = $entityManager->find(Supplier::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nesto nije u redu. Ne postoji traženi dobavljač'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($supplier);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $supplier->setData($postData);

        $entityManager->persist($supplier);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Dobaljač izmenjen'), null, Builder::SUCCESS);

        $this->redirect('/suppliers/list');
    }
}
