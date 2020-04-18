<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Form\Renderer\Json;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Role;
use Vemid\ProjectOne\Entity\Entity\User as EntityUser;

/**
 * Class UserHandler
 * @package Vemid\ProjectOne\Json\Handler
 */
class User extends AbstractHandler
{
    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        /** @var Role[] $roles */
        $roles = $entityManager->getRepository(Role::class)->findAll();
        $form = $formBuilder->build(new EntityUser());

        $roleOptions = [];
        foreach ($roles as $role) {
            $roleOptions[$role->getCode()] = $role->getName();
        }

        $form->addMultiSelect('roles', $this->translator->_('Roles'), $roleOptions)
            ->setHtmlAttribute('multiple', true)
            ->setHtmlAttribute('class', 'form-control required chosen-search');

        return (new Json())->render($form);
    }

    public function edit($id, FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
        }

        /** @var Role[] $roles */
        $roles = $entityManager->getRepository(Role::class)->findAll();
        $form = $formBuilder->build($user, ['password', 'lastIp']);

        $roleOptions = [];
        foreach ($roles as $role) {
            $roleOptions[$role->getCode()] = $role->getName();
        }

        $default = [];
        foreach ($user->getRoles() as $role) {
            $default[] = $role->getCode();
        }

        $form->addMultiSelect('roles', $this->translator->_('Roles'), $roleOptions)
            ->setHtmlAttribute('multiple', true)
            ->setDefaultValue($default)
            ->setHtmlAttribute('class', 'form-control required chosen-search');

        return (new Json())->render($form);
    }
}
