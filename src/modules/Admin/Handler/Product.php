<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\ProductFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Product as EntityProduct;

/**
 * Class Product
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Product extends AbstractHandler
{
    public function list(EntityManagerInterface $entityManager, ProductFilterForm $productFilterForm)
    {
        $products = $entityManager->getRepository(EntityProduct::class)->findBy([], null, 25, 1);

        $this->view->setTemplate('product::list.html.twig', [
            'products' => $products,
            'form' => $productFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder)
    {

        $this->view->setTemplate('product::create.html.twig', [
            'form' => $formBuilder->build(new EntityProduct)
        ]);
    }
}