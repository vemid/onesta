<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\SupplierFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Supplier as EntitySupplier;

/**
 * Class Supplier
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Code extends AbstractHandler
{
    public function list(EntityManagerInterface $entityManager, SupplierFilterForm $supplierFilterForm): void
    {
        $this->view->setTemplate('supplier::list.html.twig', [
            'form' => $supplierFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder): void
    {
        $this->view->setTemplate('supplier::create.html.twig', [
            'form' => $formBuilder->build(new EntitySupplier())
        ]);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $supplier EntitySupplier */
        if (!$supplier = $entityManager->find(EntitySupplier::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi dobavljač'), null, Builder::WARNING);
        }

        $this->view->setTemplate('supplier::update.html.twig', [
            'form' => $formBuilder->build($supplier ?: new  EntitySupplier)
        ]);
    }

    public function overview($id, EntityManagerInterface $entityManager): void
    {
        /** @var $supplier EntitySupplier */
        if (!$supplier = $entityManager->find(EntitySupplier::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi dobaljač'), null, Builder::WARNING);
        }

        $this->view->setTemplate('supplier::overview.html.twig', [
            'supplier' => $supplier
        ]);
    }
}
