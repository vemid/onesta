<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\PaymentInstallment as EntityPaymentInstallment;

/**
 * Class PaymentInstallment
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PaymentInstallment extends AbstractHandler
{
    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $paymentInstallment EntityPaymentInstallment */
        if (!$paymentInstallment = $entityManager->find(EntityPaymentInstallment::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi proizvod'), null, Builder::WARNING);
        }

        $purchase = $paymentInstallment->getPurchase();

        $counter = 1;
        foreach ($purchase->getPaymentInstallments() as $paymentInstallment) {
            if ($paymentInstallment->getId() === (int)$id) {
                break;
            }

            $counter++;
        }

        $form = $formBuilder->build($paymentInstallment ?: new  EntityPaymentInstallment);
        $form->removeComponent($form->getComponent('installmentDate'));
        $form->removeComponent($form->getComponent('installmentAmount'));

        $this->view->setTemplate('payment-installment::update.html.twig', [
            'form' => $form,
            'installment' => $counter
        ]);
    }
}
