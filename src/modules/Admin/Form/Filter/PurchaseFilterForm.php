<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form\Filter;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\Entity\Client;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class SupplierFilterForm
 * @package Vemid\ProjectOne\Admin\Form\Filter
 */
class PurchaseFilterForm extends AbstractForm
{
    /**
     * @inheritDoc
     */
    public function generate(EntityInterface $entity = null): Form
    {
        /** @var Code $entity */

        $objectRepository = $this->entityManager->getRepository(Client::class);

        $options = ['' => '-- Izaberite --'];
        /** @var Client $client */
        foreach ($objectRepository->findAll() as $client) {
            $options[$client->getId()] = (string)$client;
        }

        $optionsGuarantor = ['' => '-- Izaberite --'];
        foreach ($objectRepository->findAll() as $client) {
            if (!$client->getGuarantor()) {
                continue;
            }

            $optionsGuarantor[$client->getGuarantor()->getId()] = (string)$client->getGuarantor();
        }

        $this->form
            ->addSelect('name', $this->translator->_('Ime i Prezime'))
            ->setItems($options)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addSelect('guarantor', $this->translator->_('Garantor'))
            ->setItems($optionsGuarantor)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addText('fromDate', $this->translator->_('Od datuma'))
            ->setHtmlAttribute('class', 'form-control datepicker')
            ->setHtmlAttribute('placeholder', $this->translator->_('Od datuma'));

        $this->form
            ->addText('toDate', $this->translator->_('Do datuma'))
            ->setHtmlAttribute('class', 'form-control datepicker')
            ->setHtmlAttribute('placeholder', $this->translator->_('Do datuma'));

        $this->form
            ->addText('phoneNumber', $this->translator->_('Br. Tel'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('plates', $this->translator->_('Reg. broj'))
            ->setHtmlAttribute('class', 'form-control');

        return $this->form;
    }
}