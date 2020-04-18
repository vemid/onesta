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
class UserResetPasswordForm extends AbstractForm
{
    /**
     * {@inheritDoc}
     */
    public function generate(EntityInterface $entity = null): Form
    {
        $this->form->setAction('/auth/reset-password');
        $this->form->setHtmlAttribute('class', 'm-t');
        $this->form->setHtmlAttribute('novalidate', true);

        $this->form->addEmail('email', $this->translator->_('Email'))
            ->setRequired($this->translator->_('Please fill your email.'))
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('placeholder', $this->translator->_('Email'));

        $this->form->addSubmit('send', $this->translator->_('Reset Password'))
            ->setHtmlAttribute('class', 'btn btn-primary block full-width m-b');

        return $this->form;
    }
}
