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
class UserNewPasswordForm extends AbstractForm
{
    /**
     * {@inheritDoc}
     */
    public function generate(EntityInterface $entity = null): Form
    {
        $this->form->setAction('/auth/change-password');
        $this->form->setHtmlAttribute('class', 'm-t');
        $this->form->setHtmlAttribute('novalidate', true);

        $this->form->addHidden('userId', $entity ? (string)$entity->getEntityId() : null);

        $this->form->addEmail('email', $this->translator->_('Email'))
            ->setRequired($this->translator->_('Please fill your email.'))
            ->setHtmlAttribute('class', 'form-control')
            ->setDefaultValue($entity ? $entity->getEmail()  : '')
            ->setHtmlAttribute('placeholder', $this->translator->_('Email'))
            ->setHtmlAttribute('readonly', true);

        $this->form->addPassword('password', 'Password:')
            ->setRequired($this->translator->_('Please fill your new password.'))
            ->setHtmlAttribute('class', 'form-control required')
            ->setHtmlAttribute('placeholder', $this->translator->_('New Password'))
            ->addRule(Form::MIN_LENGTH, $this->translator->_('The password is too short: it must be at least %d characters'), 6);

        $this->form->addPassword('rePassword', 'Retype Password:')
            ->setRequired($this->translator->_('Please retype your new password.'))
            ->setHtmlAttribute('class', 'form-control required')
            ->setHtmlAttribute('placeholder', $this->translator->_('Retype Password'))
            ->addRule(Form::EQUAL, $this->translator->_('Password mismatch'), $this->form['password']);

        $this->form->addSubmit('send', $this->translator->_('Change Password'))
            ->setHtmlAttribute('class', 'btn btn-primary block full-width m-b');

        return $this->form;
    }
}
