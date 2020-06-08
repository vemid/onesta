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

        $product->setCode($code);
        $product->setName($postData['name']);
        $product->setDescription($postData['description']);

        $entityManager->persist($product);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Product added!'), null, Builder::SUCCESS);

        return $this->redirect('/products/list');
    }
}