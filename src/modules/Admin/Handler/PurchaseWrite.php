<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Entity\Entity\Client;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Purchase;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\Registration;
use Vemid\ProjectOne\Entity\Entity\Stock;
use Vemid\ProjectOne\Entity\Entity\SupplierProduct;
use \Vemid\ProjectOne\Entity\Entity\PaymentInstallment;
use Vemid\ProjectOne\Entity\Repository\PurchaseRepository;

/**
 * Class PurchaseWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PurchaseWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager)
    {
        /** @var PurchaseRepository $purchaseRepository */
        $purchaseRepository = $entityManager->getRepository(Purchase::class);
        $paymentInstallmentRepository = $entityManager->getRepository(PaymentInstallment::class);
        $purchases = $purchaseRepository->fetchPurchases($this->limit, $this->offset, $this->filterColumns);
        $totalPurchases = $purchaseRepository->fetchPurchases(0, 0);
        $filteredPurchases = $purchaseRepository->fetchPurchases(0, 0, $this->filterColumns);

        $data = [];
        foreach ($purchases as $purchase) {
            $totalPrice = $purchaseRepository->fetchTotalPrice($purchase);
            $totalPaid = $purchaseRepository->fetchTotalPaid($purchase);
            $missingPaymentInstallments = $paymentInstallmentRepository->fetchMissingInstallments($purchase);
            $todayPaymentInstallments = $paymentInstallmentRepository->fetchInstallmentsShouldPayToday($purchase);

            $client = $purchase->getClient();

            $color = '';
            if($totalPrice === $totalPaid) {
                $color = '#62FF90';
            } elseif(count($todayPaymentInstallments)) {
                $color = '#FFD967';
            } elseif(count($missingPaymentInstallments)) {
                $color = '#FF432E';
            }

            $data[] = [
                (string)$client,
                $purchase->getCreatedAt()->format('m.d.Y'),
                (string)$client->getGuarantor(),
                $client->getPhoneNumber(),
                $purchase->getRegistration() ? $purchase->getRegistration()->getPlates() : '',
                '',
                number_format($totalPrice, 2),
                HtmlTag::groupLink([
                    HtmlTag::link('/supplier-receipts/overview/' . $purchase->getId(), false, 'text-success bigger-120', 'search', false),
                    HtmlTag::link('/supplier-receipts/update/' . $purchase->getId(), false, 'text-default bigger-120', 'pencil-square-o', false),
                    HtmlTag::link('#', false, 'text-danger bigger-120', 'trash-o', false, [
                        'data-delete' => '',
                        'data-form-url' => htmlspecialchars(PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', '/supplier-receipts/delete/' . $purchase->getId())),
                        'data-title' => $this->translator->_('Obriši proizvod'),
                    ]),
                ]),
                $color
            ];
        }

        return [
            'draw' => $this->page,
            'recordsTotal' => count($totalPurchases),
            'recordsFiltered' => count($filteredPurchases),
            'data' => $data
        ];
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $purchase = new Purchase();
        $form = $formBuilder->build($purchase);

        $postData = $form->getHttpData();
        $guarantorId = $postData['guarantor'] ?? null;

        if (empty($postData['client'])) {
            $client = new Client();
            $clientForm = $formBuilder->build($client, [], false);
            $clientForm->setDefaults($_POST);

            if (!$clientForm->isValid()) {
                $this->messageBag->pushFormValidationMessages($clientForm);
                return;
            }

            if (!$guarantor = $entityManager->find(Client::class, $postData['guarantor'])) {
                $guarantor = $client->getGuarantor();
            }

            $postData['guarantor'] = $guarantor;

            $client->setData($postData);

            $entityManager->persist($client);
            $entityManager->flush();

        } else if (!$client = $entityManager->find(Client::class, $postData['client'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Client not found!'), null, Builder::DANGER);
            return;
        }

        if (!$paymentType = $entityManager->getRepository(Code::class)->findOneByCode($postData['paymentType'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Payment type not found!'), null, Builder::DANGER);
            return;
        }

        if (!$code = $entityManager->getRepository(Code::class)->findOneByCode($postData['code'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Code type not found!'), null, Builder::DANGER);
            return;
        }

        $guarantor = null;
        if ($guarantorId) {
            if (!$guarantor = $entityManager->find(Client::class, $guarantorId)) {
                $this->messageBag->pushFlashMessage($this->translator->_('Guarantor not found!'), null, Builder::DANGER);
                return;
            }
        }

        $postData['guarantor'] = $guarantor;
        $postData['code'] = $code;
        $postData['client'] = $client;
        $postData['paymentType'] = $paymentType;

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $purchase->setData($postData);
        $entityManager->persist($purchase);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Client added!'), null, Builder::SUCCESS);

        return $this->redirect('/purchases/add-items/' . $purchase->getId());
    }

    public function addRegistration($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $purchase Purchase */
        if (!$purchase = $entityManager->find(Purchase::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena kupovina'), null, Builder::WARNING);
            return;
        }

        $registration = new Registration();

        $form = $formBuilder->build($registration);
        $form->setValues($_POST);

        $postData = $form->getHttpData();
        $postData['authorization'] = $postData['authorization'] ?? 0;

        if (!$form->isValid()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $postData['purchase'] = $purchase;
        $postData['registeredUntil'] = new \DateTime($postData['registeredUntil']);

        $registration->setData($postData);

        $entityManager->persist($registration);
        $entityManager->flush();

        return $this->forward(sprintf('/purchases/registration/%s', $registration->getId()));
    }

    public function addItems($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $purchase Purchase */
        if (!$purchase = $entityManager->find(Purchase::class, $id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Porudžbina ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData = $this->request->getParsedBody();

        if (!isset($postData['postData'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Podaci ne postoji!'), null, Builder::DANGER);
            return;
        }

        $errorMessages = [];
        foreach ($postData['postData'] as $row => &$data) {
            $purchaseItem = new PurchaseItem();
            $form = $formBuilder->build($purchaseItem, [], false);
            $form->setValues($data, true);

            $form->validate();
            foreach ($form->getControls() as $control) {
                foreach ($control->getErrors() as $error) {
                    $errorMessages[$row] = ['field' => $control->getName(), 'message' => $error];
                }
            }

            if (!$supplierProduct = $entityManager->find(SupplierProduct::class, $data['supplierProduct'])) {
                $errorMessages[$row] = ['field' => 'supplierProduct', 'message' => $this->translator->_('Proizvod ne postoji!')];
            } else {
                $data['supplierProduct'] = $supplierProduct;
                $data['purchase'] = $purchase;
            }
        }

        if (count($errorMessages) === 0) {
            foreach ($postData['postData'] as $row => $data) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->setData($data);
                $purchaseItem->setType(Stock::OUTCOME);
                $entityManager->persist($purchaseItem);
            }

            $entityManager->flush();
        } else {
            $this->messageBag->pushFlashMessage($this->translator->_('Greška u podacima!'), null, Builder::DANGER);

            return ['error' => true, 'rowMessages' => $errorMessages];
        }

        return $this->redirect('/purchases/add-items/' . $id);
    }


    public function finish($id, EntityManagerInterface $entityManager)
    {
        /** @var $purchase Purchase */
        if (!$purchase = $entityManager->find(Purchase::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena kupovina'), null, Builder::WARNING);
            return;
        }
        
        if ($purchase->getFinished()) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, ne možemo zatvoriti kupovinu koja je već zatvorena!'), null, Builder::WARNING);
            return;
        }

        $purchase->setFinished(true);
        $entityManager->persist($purchase);
        $entityManager->flush();

        return ['success' => true];
    }

    public function addPaymentInstallments($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $purchase Purchase */
        if (!$purchase = $entityManager->find(Purchase::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji tražena kupovina'), null, Builder::WARNING);
            return;
        }

        if (!$purchase->getFinished()) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, ne možemo procesuirati plaćanja za kupovinu koja nije zatvorena!'), null, Builder::WARNING);
            return;
        }

        $postData = $this->request->getParsedBody();

        if (!isset($postData['postData'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Podaci ne postoji!'), null, Builder::DANGER);
            return;
        }

        $errorMessages = [];
        foreach ($postData['postData'] as $row => &$data) {
            $paymentInstallment = new PaymentInstallment();
            $form = $formBuilder->build($paymentInstallment, [], false);
            $form->setValues($data, true);

            $form->validate();
            foreach ($form->getControls() as $control) {
                foreach ($control->getErrors() as $error) {
                    $errorMessages[$row] = ['field' => $control->getName(), 'message' => $error];
                }
            }

            $data['purchase'] = $purchase;
            $data['installmentAmount'] = preg_replace('/[^\d.]/', '', $data['installmentAmount']);
            $data['installmentDate'] = new \DateTime($data['installmentDate']);
            $data['paymentDate'] = !empty($data['paymentDate']) ? new \DateTime($data['paymentDate']) : null;
            $data['paymentAmount'] = !empty($data['paymentAmount']) ? preg_replace('/[^\d.]/', '', $data['paymentAmount']) : null;
        }

        if (count($errorMessages) === 0) {
            foreach ($postData['postData'] as $row => $data) {
                $purchaseItem = new PaymentInstallment();
                $purchaseItem->setData($data);
                $entityManager->persist($purchaseItem);
            }

            $entityManager->flush();
        } else {
            $this->messageBag->pushFlashMessage($this->translator->_('Greška u podacima!'), null, Builder::DANGER);

            return ['error' => true, 'rowMessages' => $errorMessages];
        }

        return $this->redirect('/purchases/add-items/' . $id);
    }
}
