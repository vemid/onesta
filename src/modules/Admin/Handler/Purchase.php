<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\CodeFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\CodeType;
use \Vemid\ProjectOne\Entity\Entity\Purchase as EntityPurchase;
use \Vemid\ProjectOne\Entity\Entity\Client as EntityClient;
use \Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\SupplierReceipt as EntitySupplierReceipt;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem as EntitySupplierReceiptItem;

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
            'code' => 'PURCHASE_TYPE'
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

        $form = $formBuilder->build(new PurchaseItem());
        $this->view->setTemplate('purchase::add-items.html.twig', [
            'purchase' => $purchase,
            'form' => $form
        ]);
    }
}
