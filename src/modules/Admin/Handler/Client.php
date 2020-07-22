<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\TextInput;
use Vemid\ProjectOne\Admin\Form\Filter\ClientFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Client as EntityClient;
use Vemid\ProjectOne\Entity\Entity\Purchase as EntityPurchase;
use Vemid\ProjectOne\Entity\Entity\PaymentInstallment as EntityPaymentInstallment;
use Vemid\ProjectOne\Entity\Repository\PurchaseRepository;

/**
 * Class Client
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Client extends AbstractHandler
{
    public function list(ClientFilterForm $clientFilterForm): void
    {
        $this->view->setTemplate('client::list.html.twig', [
            'form' => $clientFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder): void
    {
        $guarantorComponent = new TextInput($this->translator->translate('Garantor'));
        $guarantorComponent->setHtmlAttribute('class', 'form-control');

        $form = $formBuilder->build(new EntityClient());
        $form->addComponent($guarantorComponent, 'guarantorId', 'phoneNumber');
        $form->addHidden('guarantor')->setHtmlAttribute('id', 'guarantor');

        $this->view->setTemplate('client::create.html.twig', [
            'form' => $form
        ]);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $client EntityClient */
        if (!$client = $entityManager->find(EntityClient::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi klijent'), null, Builder::WARNING);
        }

        $form = $formBuilder->build($client);

        $clientId = new HiddenField($id);

        $guarantorComponent = new TextInput($this->translator->translate('Garantor'));
        $guarantorComponent->setValue($client->getGuarantor() ? (string)$client->getGuarantor() : '');
        $guarantorComponent->setHtmlAttribute('class', 'form-control');

        $form->addComponent($guarantorComponent, 'guarantorId', 'phoneNumber');
        $form->addComponent($clientId, 'clientId');
        $form->addHidden('guarantor', ($client->getGuarantor() ? (string)$client->getGuarantor()->getId() : ''))
            ->setHtmlAttribute('id', 'guarantor');

        $this->view->setTemplate('client::update.html.twig', [
            'form' => $form
        ]);
    }

    public function overview($id, EntityManagerInterface $entityManager)
    {
        /** @var $client EntityClient */
        if (!$client = $entityManager->find(EntityClient::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi klijent'), null, Builder::WARNING);
        }

        /** @var PurchaseRepository $purchaseRepository */
        $purchaseRepository = $entityManager->getRepository(EntityPurchase::class);
        $purchaseItemRepository = $entityManager->getRepository(EntityPaymentInstallment::class);

        $purchases = [];
        /** @var EntityPurchase $purchase */
        foreach ($client->getPurchases() as $purchase) {
            $missingInstallments = $purchaseItemRepository->fetchMissingInstallments($purchase);

            $totalMissingInstallments = 0;
            foreach ($missingInstallments as $missingInstallment) {
                $totalMissingInstallments += $missingInstallment->getInstallmentAmount() - $missingInstallment->getPaymentAmount();
            }

            $purchases[] = [
                'purchase' => $purchase,
                'total' => $purchaseRepository->fetchTotalPrice($purchase),
                'totalMissingInstallments' => $totalMissingInstallments,
                'registration' => $purchase->getRegistration()
            ];
        }

        $this->view->setTemplate('client::overview.html.twig', [
            'client' => $client,
            'purchases' => $purchases
        ]);
    }
}
