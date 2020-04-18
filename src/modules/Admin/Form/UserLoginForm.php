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
class UserLoginForm extends AbstractForm
{
    /**
     * {@inheritDoc}
     */
    public function generate(EntityInterface $entity = null): Form
    {
        $this->form->setAction('/auth/login');
        $this->form->setHtmlAttribute('class', 'm-t');
        $this->form->setHtmlAttribute('novalidate', true);

        $this->form->addText('username', $this->translator->_('Username'))
            ->setRequired($this->translator->_('Please fill your username.'))
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('placeholder', $this->translator->_('Username'));
        ;

        $this->form->addPassword('password', $this->translator->_('Password:'))
            ->setRequired($this->translator->_('Please fill your password.'))
            ->setHtmlAttribute('class', 'form-control required')
            ->setHtmlAttribute('placeholder', $this->translator->_('Password'));

        $this->form->addSubmit('send', $this->translator->_('Login'))
            ->setHtmlAttribute('class', 'btn btn-primary block full-width m-b');

        return $this->form;
    }
}
