<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Code;
use \Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Role;

/**
 * Class ProductWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class ProductWrite extends AbstractHandler
{
    public function list()
    {
        $queryParams = $this->request->getParsedBody();

        $page = $queryParams['draw'] ?? 1;
        $offset = $queryParams['start'] ?? 0;
        $limit = $queryParams['length'] ?? 50;
        $search = $queryParams['search']['value'] ?? null;

        $data[] = [
            'a',
            'b',
            'c',
            'd',
        ];

       return [
            'draw' => 1,
            'recordsTotal' => 2,
            'recordsFiltered' => 2,
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