<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Supplier;

/**
 * Class SupplierWrite
 * @package Vemid\ProjectOne\Form\Handler
 */
class SupplierWrite extends AbstractHandler
{
    public function delete($id, EntityManagerInterface $entityManager)
    {
        /** @var $supplier Supplier */
        if (!$supplier = $entityManager->find(Supplier::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nešto nije u redu. Ne možemo da obrišemo rekord'), null, Builder::WARNING);
            return;
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Obrisan rekord'), null, Builder::SUCCESS);
    }
}
