<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Html;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Entity\Entity\Code;
use \Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Repository\ProductRepository;

/**
 * Class ProductWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class ProductWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager)
    {
        /** @var ProductRepository $productRepository */
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->fetchProducts($this->limit, $this->offset, $this->filterColumns);
        $totalProducts = $productRepository->fetchProducts(0, 0);
        $filteredProducts = $productRepository->fetchProducts(0, 0, $this->filterColumns);

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                (string)$product->getCode(),
                $product->getName(),
                $product->getDescription(),
                HtmlTag::groupLink([
                    HtmlTag::link('/products/overview/' . $product->getId(), false, 'text-success bigger-120', 'search', false),
                    HtmlTag::link('/products/update/' . $product->getId(), false, 'text-default bigger-120', 'pencil-square-o', false),
                    HtmlTag::link('/products/update/' . $product->getId(), false, 'text-danger bigger-120', 'trash-o', false),
                ])
            ];
        }

       return [
            'draw' => $this->page,
            'recordsTotal' => count($totalProducts),
            'recordsFiltered' => count($filteredProducts),
            'data' => $data
        ];
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $product = new Product();

        $form = $formBuilder->build($product);
        $postData = $form->getHttpData();

        if (!$form->isValid()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$code = $entityManager->find(Code::class, $postData['code'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Category not found!'), null, Builder::DANGER);
            return;
        }

        $product->setData($postData);

        $entityManager->persist($product);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Product added!'), null, Builder::SUCCESS);

        return $this->redirect('/products/list');
    }
}