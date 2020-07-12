<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Stock;
use Vemid\ProjectOne\Entity\Entity\SupplierProduct;

/**
 * Class SupplierProductWrite
 * @package Vemid\ProjectOne\Form\Handler
 */
class SupplierProductWrite extends AbstractHandler
{
    public function getQty(EntityManagerInterface $entityManager)
    {
        $body = $this->request->getParsedBody();
        /** @var $supplierProduct SupplierProduct */
        if (!$supplierProduct = $entityManager->find(SupplierProduct::class, (int)$body['id'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi klijent'), null, Builder::WARNING);
            return;
        }

        $stockQty = 0;
        /** @var Stock $stock */
        foreach ($supplierProduct->getStocks() as $stock) {
            if ($stock->getType() === Stock::INCOME) {
                $stockQty += $stock->getQty();
            } else {
                $stockQty -= $stock->getQty();
            }
        }

        $owner = $supplierProduct->getSupplier()->getOwner();

        return ['qty' => !$owner ? $stockQty : 1, 'price' => $supplierProduct->getRetailPrice(), 'disableQty' => !$owner];
    }
}
