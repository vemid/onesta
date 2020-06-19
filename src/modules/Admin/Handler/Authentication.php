<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Doctrine\ORM\EntityManagerInterface;
use PragmaRX\Google2FA\Google2FA;
use Vemid\ProjectOne\Admin\Form\User2FaForm;
use Vemid\ProjectOne\Admin\Form\User2FaSetupForm;
use Vemid\ProjectOne\Admin\Form\UserLoginForm;
use Vemid\ProjectOne\Admin\Form\UserNewPasswordForm;
use Vemid\ProjectOne\Admin\Form\UserResetPasswordForm;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\User as EntityUser;

/**
 * Class UserRead
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Authentication extends AbstractHandler
{
    /**
     * @param UserLoginForm $userLoginForm
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(UserLoginForm $userLoginForm)
    {
        header('Require-Auth: 1');
        $this->view->setTemplate('auth::login-form.html.twig', ['form' => $userLoginForm->getForm()]);
    }

    public function g2faSetup($id, EntityManagerInterface $entityManager, Google2FA $google2FA, User2FaSetupForm $form)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, $id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
            return;
        }

        $key = $google2FA->generateSecretKey();

        $g2faUrl = $google2FA->getQRCodeUrl(
            (string)$user,
            $user->getEmail(),
            $key
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new ImagickImageBackEnd()
            )
        );

        $form = $form->generate($user);
        $form['auth']->setValue($key);

        $qrCodeImage = base64_encode($writer->writeString($g2faUrl));
        $this->view->setTemplate('auth::2fa-setup.html.twig', [
            'qrCodeImage' => $qrCodeImage,
            'form' => $form
        ]);
    }

    public function g2fa($id, EntityManagerInterface $entityManager, Google2FA $google2FA, User2FaForm $form)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, $id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
            return;
        }

        $qrCodeImage = null;
        if (!$user->getSecretKey()) {
            $user->setSecretKey($google2FA->generateSecretKey());

            $entityManager->persist($user);
            $entityManager->flush();

            $g2faUrl = $google2FA->getQRCodeUrl(
                (string)$user,
                $user->getEmail(),
                $user->getSecretKey()
            );

            $writer = new Writer(
                new ImageRenderer(
                    new RendererStyle(200),
                    new ImagickImageBackEnd()
                )
            );

            $qrCodeImage = base64_encode($writer->writeString($g2faUrl));
        }

        $this->view->setTemplate('auth::2fa.html.twig', [
            'qrCodeImage' => $qrCodeImage,
            'form' => $form->generate($user)
        ]);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        $this->session->unset('user');
        $this->messageBag->pushFlashMessage($this->translator->_('Dovidjenja!'), null, Builder::SUCCESS);

        return $this->redirect('/');
    }

    public function resetPassword(UserResetPasswordForm $form)
    {
        $this->view->setTemplate('auth::reset-form.html.twig', ['form' => $form->generate()]);
    }

    public function newPassword(EntityManagerInterface $entityManager, UserNewPasswordForm $newPasswordForm)
    {
        $queryParams = $this->request->getQueryParams();

        $qBuilder = $entityManager->createQueryBuilder();
        $users = $qBuilder
            ->select('u')
            ->from(EntityUser::class, 'u')
            ->where('sha1(u.email) = :email')
            ->andWhere('u.isActive = :active')
            ->setParameters([
                'email' => $queryParams['x-request-id'],
                'active' => true
            ])
            ->setMaxResults(1)
            ->getQuery()->execute();

        if (count($users) === 0) {
            $this->messageBag->pushFlashMessage($this->translator->_('Wrong hash'), null, Builder::DANGER);
        }

        $this->view->setTemplate('auth::new-password.html.twig', [
            'form' => $newPasswordForm->generate($users[0]),
            'user' => $users[0]
        ]);
    }
}
