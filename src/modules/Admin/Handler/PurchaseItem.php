<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem as EntityPurchaseItem;

/**
 * Class PurchaseItem
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PurchaseItem extends AbstractHandler
{
    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $purchaseItem EntityPurchaseItem */
        if (!$purchaseItem = $entityManager->find(EntityPurchaseItem::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena prijemnica'), null, Builder::WARNING);
        }

        $this->view->setTemplate('purchase-item::update.html.twig', [
            'form' => $formBuilder->build($purchaseItem),
        ]);
    }
}
