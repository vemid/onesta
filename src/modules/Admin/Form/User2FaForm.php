<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class UserLoginForm
 * @package Vemid\ProjectOne\Admin\Form
 */
class User2FaForm extends AbstractForm
{
    /**
     * {@inheritDoc}
     */
    public function generate(EntityInterface $entity = null): Form
    {
        $this->form->setAction('/auth/g2fa');
        $this->form->setHtmlAttribute('class', 'm-t');
        $this->form->setHtmlAttribute('novalidate', true);

        $this->form->addHidden('userId', $entity ? (string)$entity->getEntityId() : null);

        $this->form->addEmail('code', $this->translator->_('Auth Code'))
            ->setRequired($this->translator->_('Please fill secret code.'))
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('placeholder', $this->translator->_('Auth Code'));

        $this->form->addSubmit('send', $this->translator->_('Authenticate yourself'))
            ->setHtmlAttribute('class', 'btn btn-primary block full-width m-b');

        return $this->form;
    }
}
