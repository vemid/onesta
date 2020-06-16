<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Html;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\Avatar;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Entity\Entity\Code;
use \Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Repository\ProductRepository;

/**
 * Class ProductWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class ProductWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager): array
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
                    HtmlTag::link('#', false, 'text-danger bigger-120', 'trash-o', false, [
                        'data-delete' => '',
                        'data-form-url' => htmlspecialchars(PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', '/product/delete/' . $product->getId())),
                        'data-title' => $this->translator->_('Obriši proizvod'),
                    ]),
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

        $postData['code'] = $code;
        $product->setData($postData);

        $entityManager->persist($product);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Product added!'), null, Builder::SUCCESS);

        return $this->redirect('/products/list');
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $product Product */
        if (!$product = $entityManager->find(Product::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($product);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$code = $entityManager->find(Code::class, $postData['code'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Category not found!'), null, Builder::DANGER);
            return;
        }

        $postData['code'] = $code;
        $product->setData($postData);

        $entityManager->persist($product);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Record updated'), null, Builder::SUCCESS);

        $this->redirect('/products/list/' . $product->getId());
    }
}
