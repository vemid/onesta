<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;

/**
 * Class PurchaseItemWrite
 * @package Vemid\ProjectOne\Form\Handler
 */
class PurchaseItemWrite extends AbstractHandler
{
    public function delete($id, EntityManagerInterface $entityManager)
    {
        /** @var $purchaseItem PurchaseItem */
        if (!$purchaseItem = $entityManager->find(PurchaseItem::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nešto nije u redu. Ne možemo da obrišemo rekord'), null, Builder::WARNING);
            return;
        }

        $entityManager->remove($purchaseItem);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Obrisan rekord'), null, Builder::SUCCESS);
    }
}
