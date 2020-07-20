<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form\Filter;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\Entity\Client;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class ClientFilterForm
 * @package Vemid\ProjectOne\Admin\Form\Filter
 */
class ClientFilterForm extends AbstractForm
{
    /**
     * @inheritDoc
     */
    public function generate(EntityInterface $entity = null): Form
    {
        $objectRepository = $this->entityManager->getRepository(Client::class);

        $options = ['' => '-- Izaberite --'];
        foreach ($objectRepository->findAll() as $client) {
            $options[$client->getId()] = (string)$client;
        }

        $this->form
            ->addSelect('name', $this->translator->_('Ime'))
            ->setItems($options)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addText('lastName', $this->translator->_('Prezime'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('phoneNumber', $this->translator->_('Tel. broj'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('email', $this->translator->_('Email'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('city', $this->translator->_('Grad'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('pib', $this->translator->_('PIB'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('jmbg', $this->translator->_('JMBG'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('address', $this->translator->_('Adresa'))
            ->setHtmlAttribute('class', 'form-control');

        return $this->form;
    }
}