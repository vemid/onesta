<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\UserChangePassword;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\User as EntityUser;

/**
 * Class User
 * @package Vemid\ProjectOne\Admin\Handler
 */
class User extends AbstractHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param FormBuilderInterface $formBuilder
     * @param UserChangePassword $changePassword
     * @return string
     */
    public function profile(EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder, UserChangePassword $changePassword): void
    {
        $user = $this->session->get('user');
        if (!$user = $entityManager->find(EntityUser::class, $user['id'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
        }

        $form = $formBuilder->build($user);
        $form->setAction('/form/user/update-profile/' . $user->getId());

        $this->view->setTemplate('user::profile.html.twig', [
            'user' => $user,
            'form' => $form,
            'formChangePassword' => $changePassword->generate($user)
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function management(EntityManagerInterface $entityManager): void
    {
        $this->view->setTemplate('user::management.html.twig', [
            'users' => $entityManager->getRepository(EntityUser::class)->findAll(),
        ]);
    }
}
