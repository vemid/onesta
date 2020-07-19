<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\CodeFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\CodeType;
use Vemid\ProjectOne\Entity\Entity\PaymentInstallment;
use \Vemid\ProjectOne\Entity\Entity\Purchase as EntityPurchase;
use \Vemid\ProjectOne\Entity\Entity\Client as EntityClient;
use \Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\Registration;

/**
 * Class Purchase
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Purchase extends AbstractHandler
{
    public function list(CodeFilterForm $codeFilterForm): void
    {
        $this->view->setTemplate('purchase::list.html.twig', [
            'form' => $codeFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager): void
    {
        $clientForm = $formBuilder->build(new EntityClient());
        $form = $formBuilder->build(new EntityPurchase());

        $paymentCodeType = $entityManager->getRepository(CodeType::class)->findOneByCode([
            'code' => 'PAYMENT_TYPE'
        ]);

        $paymentCodes = $entityManager->getRepository(Code::class)->findByCodeType([
            'codeType' => $paymentCodeType
        ]);

        $paymentOptions = ['' => '-- Izaberite --'];
        foreach ($paymentCodes as $paymentCode) {
            $paymentOptions[$paymentCode->getCode()] = $paymentCode->getName();
        }

        $purchaseCodeType = $entityManager->getRepository(CodeType::class)->findOneByCode([
            'code' => 'SHOP'
        ]);

        $purchaseCodes = $entityManager->getRepository(Code::class)->findByCodeType([
            'codeType' => $purchaseCodeType
        ]);

        $purchaseOptions = ['' => '-- Izaberite --'];
        foreach ($purchaseCodes as $purchaseCode) {
            $purchaseOptions[$purchaseCode->getCode()] = $purchaseCode->getName();
        }

        $form->getComponent('paymentType')->setItems($paymentOptions);
        $form->getComponent('code')->setItems($purchaseOptions);

        foreach ($clientForm->getComponents() as $component) {
            $type = $component->getControl()->getAttribute('type');
            if ($type === 'hidden') {
                continue;
            }

            $clientForm->removeComponent($component);
            $form->addComponent($component, $component->getName(), 'note');
        }

        $guarantorComponent = $form->getComponent('guarantor');
        $form->removeComponent($guarantorComponent);
        $form->addComponent($guarantorComponent, 'guarantorId', 'phoneNumber');
        $form->addHidden('guarantor')->setHtmlAttribute('id', 'guarantor');

        $this->view->setTemplate('purchase::create.html.twig', [
            'form' => $form
        ]);
    }

    public function addItems($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $purchase EntityPurchase */
        if (!$purchase = $entityManager->find(EntityPurchase::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi klijent'), null, Builder::WARNING);
            return;
        }

        $purchaseRepository = $entityManager->getRepository(EntityPurchase::class);
        $paymentInstallmentRepository = $entityManager->getRepository(PaymentInstallment::class);
        $totalPrice = $purchaseRepository->fetchTotalPrice($purchase);
        $assignedInstalmentsAmount = $paymentInstallmentRepository->fetchTotalInstalmentPrice($purchase);

        $form = $formBuilder->build(new PurchaseItem());
        $registrationForm = $formBuilder->build(new Registration());
        $paymentInstallmentForm = $formBuilder->build(new PaymentInstallment());

        $values = $form->getComponent('supplierProduct')->getItems();
        foreach ($purchase->getPurchaseItems() as $purchaseItem) {
            unset($values[$purchaseItem->getSupplierProduct()->getId()]);
        }

        $form->getComponent('supplierProduct')->setItems($values);

        $registrationForm->setAction('/purchases/add-registration/' . $purchase->getId());
        $registrationForm->getComponent('purchase')->setValue($id);

        $dateTime = $purchase->getCreatedAt();

        $priceToPay = $totalPrice - $assignedInstalmentsAmount;
        $instalments = 4 - $purchase->getPaymentInstallments()->count();
        $date = clone $dateTime;

        if ($instalments) {
            $date->modify(sprintf('+%d months', $purchase->getPaymentInstallments()->count()));

            $paymentInstallmentForm->getComponent('installmentDate')->setValue($date->format('Y-m-d'));
            $paymentInstallmentForm->getComponent('installmentAmount')->setDefaultValue(number_format(round($priceToPay / $instalments, 2), 2));
        }

        $date1 = clone $date;
        $date1->modify('+1 month');
        $date2 = clone $date;
        $date2->modify('+2 month');
        $date3 = clone $date;
        $date3->modify('+3 month');

        $this->view->setTemplate('purchase::add-items.html.twig', [
            'purchase' => $purchase,
            'form' => $form,
            'registrationForm' => $registrationForm,
            'totalPrice' => $totalPrice,
            'paymentInstallmentForm' => $paymentInstallmentForm,
            'instalmentDates' => ['date1' => $date1->format('Y-m-d'), 'date2' => $date2->format('Y-m-d'), 'date3' => $date3->format('Y-m-d')],
            'assignedInstalmentsAmount' => $assignedInstalmentsAmount,
            'today' => new \DateTime()
        ]);
    }

    public function registration($id, EntityManagerInterface $entityManager)
    {
        /** @var $registration Registration */
        if (!$registration = $entityManager->find(Registration::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi klijent'), null, Builder::WARNING);
            return;
        }

        $this->view->setTemplate('purchase::view-registration.html.twig', [
            'registration' => $registration
        ]);
    }
}
