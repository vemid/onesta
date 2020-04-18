<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\Entity\User;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class UserChangePassword
 * @package Vemid\ProjectOne\Admin\Form
 */
class UserChangePassword extends AbstractForm
{
    /**
     * {@inheritDoc}
     */
    public function generate(EntityInterface $entity = null): Form
    {
        /** @var User $entity */
        if ($entity) {
            $this->form->setAction('/form/user/change-password/' . $entity->getId());
            $this->form->setHtmlAttribute('class', 'm-t');
            $this->form->setHtmlAttribute('novalidate', true);

            $this->form->addPassword('oldPassword', $this->translator->_('Old Password'))
                ->setRequired($this->translator->_('Please fill old password.'))
                ->setHtmlAttribute('class', 'form-control required')
                ->setHtmlAttribute('placeholder', $this->translator->_('Old Password'));

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

            $this->form->addSubmit('send', $this->translator->_('Login'))
                ->setHtmlAttribute('class', 'btn btn-primary block full-width m-b');
        }

        return $this->form;
    }
}