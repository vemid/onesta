<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\ProductFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Product as EntityProduct;

/**
 * Class Product
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Product extends AbstractHandler
{
    public function list(ProductFilterForm $productFilterForm): void
    {
        $this->view->setTemplate('product::list.html.twig', [
            'form' => $productFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder): void
    {
        $this->view->setTemplate('product::create.html.twig', [
            'form' => $formBuilder->build(new EntityProduct)
        ]);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $product EntityProduct */
        if (!$product = $entityManager->find(EntityProduct::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi proizvod'), null, Builder::WARNING);
        }

        $this->view->setTemplate('product::update.html.twig', [
            'form' => $formBuilder->build($product ?: new  EntityProduct)
        ]);
    }

    public function overview($id, EntityManagerInterface $entityManager): void
    {
        /** @var $product EntityProduct */
        if (!$product = $entityManager->find(EntityProduct::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi proizvod'), null, Builder::WARNING);
        }

        $this->view->setTemplate('product::overview.html.twig', [
            'product' => $product
        ]);
    }
}
