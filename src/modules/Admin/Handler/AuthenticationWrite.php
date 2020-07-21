<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use PragmaRX\Google2FA\Google2FA;
use Vemid\ProjectOne\Admin\Form\User2FaForm;
use Vemid\ProjectOne\Admin\Form\User2FaSetupForm;
use Vemid\ProjectOne\Admin\Form\UserLoginForm;
use Vemid\ProjectOne\Admin\Form\UserNewPasswordForm;
use Vemid\ProjectOne\Admin\Form\UserResetPasswordForm;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\ProjectOne\Common\Helper\Avatar;
use Vemid\ProjectOne\Common\Mailer\MailManagerInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Common\Session\FlashSession;
use Vemid\ProjectOne\Entity\Entity\User as EntityUser;
use Vemid\ProjectOne\Entity\Repository\UserRepository;

/**
 * Class UserRead
 * @package Vemid\ProjectOne\Admin\Handler
 */
class AuthenticationWrite extends AbstractHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserLoginForm $userLoginForm
     * @throws \Exception
     */
    public function login(EntityManagerInterface $entityManager, UserLoginForm $userLoginForm)
    {
        $form = $userLoginForm->getForm();
        $params = $this->request->getQueryParams();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);

            return;
        }

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(EntityUser::class);
        $post = $form->getHttpData();

        /** @var \Vemid\ProjectOne\Entity\Entity\User $user */
        $user = $userRepository->findOneBy([
            'username' => $post['username'],
            'isActive' => true
        ]);

        if (!$user) {
            $this->messageBag->pushFlashMessage($this->translator->_('Wrong username'), null, Builder::DANGER);
            return;
        }

        if (password_verify($post['password'], $user->getPassword())) {
            if (filter_var($this->config->get('2fa'), FILTER_VALIDATE_BOOLEAN)) {
                if ($user->getSecretKey()) {
                    return $this->forward(sprintf('/auth/g2fa/%s%s', $user->getId(), isset($params['redirect']) ? '?redirect=' . $params['redirect'] : ''));
                }

                return $this->forward('/auth/g2fa-setup/' . $user->getId());
            }

            $user->setLastVisitDatetime(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();

            $this->session->set('user', [
                'id' => $user->getId(),
                'name' => $user->getDisplayName(),
                'avatar' => (new Avatar())->getUserAvatar($user)
            ]);

            $flashSession = new FlashSession($this->session);
            $flashSession->success($this->translator->_(sprintf('Welcome %s', (string)$user)));
            header('Require-Auth: 0');

            return $this->redirect($params['redirect'] ?? '/');
        }

        $this->messageBag->pushFlashMessage($this->translator->_('Wrong password'), null, Builder::DANGER);
        return;
    }

    public function g2faSetup(EntityManagerInterface $entityManager, Google2FA $google2FA, User2FaSetupForm $form)
    {
        $form = $form->getForm();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);

            return;
        }

        $post = $form->getHttpData();

        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, $post['userId'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::DANGER);
        }

        $user->setSecretKey($post['auth']);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->forward('/auth/g2fa/' . $user->getId());
    }

    public function g2fa(EntityManagerInterface $entityManager, Google2FA $google2FA, User2FaForm $form)
    {
        $form = $form->getForm();
        $params = $this->request->getQueryParams();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);

            return;
        }

        $post = $form->getHttpData();

        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, $post['userId'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::DANGER);
        }

        $valid = $google2FA->verifyKey($user->getSecretKey(), $post['code']);

        if (!$valid) {
            $this->messageBag->pushFlashMessage($this->translator->_('Authentication failed!'), null, Builder::DANGER);
            return;
        }

        $user->setLastVisitDatetime(new \DateTime());
        $entityManager->persist($user);
        $entityManager->flush();

        $this->session->set('user', [
            'id' => $user->getId(),
            'name' => $user->getDisplayName(),
            'avatar' => (new Avatar())->getUserAvatar($user)
        ]);

        $flashSession = new FlashSession($this->session);
        $flashSession->success($this->translator->_(sprintf('Welcome %s', (string)$user)));

        return $this->redirect($params['redirect'] ?? '/');
    }

    public function resetPassword(UserResetPasswordForm $userResetPasswordForm, EntityManagerInterface $entityManager, MailManagerInterface $mailer, ConfigInterface $config)
    {
        $form = $userResetPasswordForm->generate();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);

            return;
        }

        $post = $form->getHttpData();

        /** @var EntityUser $user */
        $user = $entityManager->getRepository(EntityUser::class)->findOneBy([
            'email' => $post['email'],
            'isActive' => true
        ]);

        if (!$user) {
            $this->messageBag->pushFlashMessage($this->translator->_('Wrong email, try again'), null, Builder::DANGER);
            return;
        }

        $message = $mailer->createMessageFromView('email::reset-password.html.twig', [
            'url' => sprintf('%s/auth/new-password?x-request-id=%s', $config->get('site'), sha1($user->getEmail())),
        ]);

        $message->setTo($post['email']);
        $message->setSubject('Forgot your password');
        $message->setReplyTo('darko.vesic@arbor-education.com');

        if (!$message->send()) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something went wrong, please try later'), null, Builder::DANGER);
            return;
        }

        $this->messageBag->pushFlashMessage($this->translator->_('Hm, something went wrong, please try later'), null, Builder::SUCCESS);
        $this->redirect('/auth/login');
    }

    public function changePassword(UserNewPasswordForm $newPasswordForm, EntityManagerInterface $entityManager)
    {
        $form = $newPasswordForm->generate();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);

            return;
        }

        $post = $form->getHttpData();

        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, $post['userId'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::DANGER);
        }

        $user->setPassword(password_hash($post['password'], PASSWORD_BCRYPT));
        $entityManager->persist($user);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Password changed. Please use new password'), null, Builder::SUCCESS);

        $this->redirect('/auth/login');
    }
}
