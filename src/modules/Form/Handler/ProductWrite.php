<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Product;

/**
 * Class Product
 * @package Vemid\ProjectOne\Form\Handler
 */
class ProductWrite extends AbstractHandler
{
    public function delete($id, EntityManagerInterface $entityManager)
    {
        /** @var $product Product */
        if (!$product = $entityManager->find(Product::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
            return;
        }

        $entityManager->remove($product);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Record deleted'), null, Builder::SUCCESS);
    }
}
