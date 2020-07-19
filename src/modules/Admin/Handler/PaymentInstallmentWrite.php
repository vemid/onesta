<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\PaymentInstallment;

/**
 * Class SupplierWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PaymentInstallmentWrite extends AbstractHandler
{

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $paymentInstallment PaymentInstallment */
        if (!$paymentInstallment = $entityManager->find(PaymentInstallment::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nesto nije u redu. Ne postoji traženi dobavljač'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($paymentInstallment);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $postData['installmentAmount'] = preg_replace('/[^\d.]/', '', $postData['installmentAmount']);
        $postData['installmentDate'] = new \DateTime($postData['installmentDate']);
        $postData['paymentDate'] = !empty($postData['paymentDate']) ? new \DateTime($postData['paymentDate']) : null;
        $postData['paymentAmount'] = !empty($postData['paymentAmount']) ? preg_replace('/[^\d.]/', '', $postData['paymentAmount']) : null;

        $paymentInstallment->setData($postData);

        $entityManager->persist($paymentInstallment);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Uplata dodata'), null, Builder::SUCCESS);

        $this->redirect('/purchases/add-items/' . $paymentInstallment->getPurchase()->getId());
    }
}
