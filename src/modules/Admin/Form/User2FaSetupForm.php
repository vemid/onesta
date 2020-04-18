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
class User2FaSetupForm extends AbstractForm
{
    /**
     * {@inheritDoc}
     */
    public function generate(EntityInterface $entity = null): Form
    {
        $this->form->setAction('/auth/g2fa-setup');
        $this->form->setHtmlAttribute('class', 'm-t');
        $this->form->setHtmlAttribute('novalidate', true);

        $this->form->addHidden('userId', $entity ? (string)$entity->getEntityId() : null);
        $this->form->addHidden('auth');

        $this->form->addSubmit('send', $this->translator->_('Next'))
            ->setHtmlAttribute('class', 'btn btn-primary block full-width m-b');

        return $this->form;
    }
}
