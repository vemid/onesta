<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Client;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Purchase;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\Registration;
use Vemid\ProjectOne\Entity\Entity\Stock;
use Vemid\ProjectOne\Entity\Entity\SupplierProduct;

/**
 * Class PurchaseWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PurchaseWrite extends AbstractHandler
{
    public function list()
    {

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

            $postData['guarantor'] = $client->getGuarantor();

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
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi klijent'), null, Builder::WARNING);
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
}
