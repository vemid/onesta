<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form\Filter;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
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
        $this->form
            ->addText('name', $this->translator->_('Ime'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('phoneNumber', $this->translator->_('Tel. broj'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('email', $this->translator->_('Email'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('address', $this->translator->_('Adresa'))
            ->setHtmlAttribute('class', 'form-control');

        return $this->form;
    }
}