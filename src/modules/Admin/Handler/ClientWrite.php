<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\CodeType;
use Vemid\ProjectOne\Entity\Repository\ClientRepository;
use \Vemid\ProjectOne\Entity\Entity\Client as EntityClient;

/**
 * Class CodeWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class ClientWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager): array
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = $entityManager->getRepository(EntityClient::class);
        $clients = $clientRepository->fetchClients($this->limit, $this->offset, $this->filterColumns);
        $totalCodes = $clientRepository->fetchClients(0, 0);
        $filteredCodes = $clientRepository->fetchClients(0, 0, $this->filterColumns);

        $data = [];
        foreach ($clients as $client) {
            $data[] = [
                (string)$client,
                $client->getEmail(),
                $client->getPhoneNumber(),
                $client->getJmbg(),
                $client->getPib(),
                $client->getCity(),
                $client->getAddress(),
                HtmlTag::groupLink([
                    HtmlTag::link('/clients/update/' . $client->getId(), false, 'text-default bigger-120', 'pencil-square-o', false),
                    HtmlTag::link('#', false, 'text-danger bigger-120', 'trash-o', false, [
                        'data-delete' => '',
                        'data-form-url' => htmlspecialchars(PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', '/clients/delete/' . $client->getId())),
                        'data-title' => $this->translator->_('Obriši Kategoriju'),
                    ]),
                ])
            ];
        }

        return [
            'draw' => $this->page,
            'recordsTotal' => count($totalCodes),
            'recordsFiltered' => count($filteredCodes),
            'data' => $data
        ];
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $client = new EntityClient();

        $form = $formBuilder->build($client);
        $postData = $form->getHttpData();
        $form->setValues($_POST);

        if (!$form->isValid()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!empty($postData['guarantor']) && !$guarantor = $entityManager->find(EntityClient::class, $postData['guarantor'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Garantor ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData['guarantor'] = $guarantor ?? null;
        $client->setData($postData);

        $entityManager->persist($client);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Klijent dodata!'), null, Builder::SUCCESS);

        return $this->redirect('/clients/list');
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $client EntityClient */
        if (!$client = $entityManager->find(EntityClient::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nesto nije u redu. Ne postoji traženi klijent'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($client);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!empty($postData['guarantor']) && !$guarantor = $entityManager->find(EntityClient::class, $postData['guarantor'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Garantor ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData['guarantor'] = $guarantor ?? null;
        $client->setData($postData);

        $entityManager->persist($client);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Klijent izmenjen'), null, Builder::SUCCESS);

        $this->redirect('/clients/list');
    }
}
