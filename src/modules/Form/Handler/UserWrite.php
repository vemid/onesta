<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\UserChangePassword;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\Avatar;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\Role;
use Vemid\ProjectOne\Entity\Entity\User as EntityUser;
use Vemid\ProjectOne\Entity\Entity\UserRoleAssignment;

/**
 * Class UserHandler
 * @package Vemid\ProjectOne\Json\Handler
 */
class UserWrite extends AbstractHandler
{
    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $user = new EntityUser();

        $form = $formBuilder->build($user, ['lastId']);

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $postData = $form->getHttpData();
        $user->setData($postData);

        $entityManager->persist($user);

        $uploadedFiles = $this->request->getUploadedFiles();
        if (count($uploadedFiles)) {
            $avatarHelper = new Avatar($entityManager);
            $avatarHelper->uploadAvatar(array_pop($uploadedFiles), $user);
        }

        if (isset($postData['roles'])) {
            foreach ($postData['roles'] as $role) {
                $role = $entityManager->getRepository(Role::class)->findOneByCode($role);
                if (!$role) {
                    $this->messageBag->pushFlashMessage($this->translator->_('Role not found!'), null, Builder::DANGER);
                    break;
                }

                $userRoleAssignment = new UserRoleAssignment();
                $userRoleAssignment->setData([
                    'user' => $user,
                    'role' => $role
                ]);
                $entityManager->persist($userRoleAssignment);
            }
        }

        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('User added!'), null, Builder::SUCCESS);
    }

    public function edit($id, FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
        }

        $form = $formBuilder->build($user, ['password', 'lastIp']);

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $postData = $form->getHttpData();

        $uploadedFiles = $this->request->getUploadedFiles();
        if (count($uploadedFiles)) {
            $avatarHelper = new Avatar($entityManager);
            $user = $avatarHelper->uploadAvatar(array_pop($uploadedFiles), $user);
        }

        $user->setUsername($postData['username']);
        $user->setFirstName($postData['firstName']);
        $user->setLastName($postData['lastName']);
        $user->setGender($postData['gender']);
        $user->setEmail($postData['email']);
        $user->setIsActive(isset($postData['isActive']) ? 1 : 0);
        $entityManager->persist($user);


        $user->getUserRoleAssignments()->forAll(static function($key, $entity) use($entityManager) {
            $entityManager->remove($entity);
            return true;
        });

        if (isset($postData['roles'])) {
            foreach ($postData['roles'] as $role) {
                $role = $entityManager->getRepository(Role::class)->findOneByCode($role);
                if (!$role) {
                    $this->messageBag->pushFlashMessage($this->translator->_('Role not found!'), null, Builder::DANGER);
                    break;
                }

                $userRoleAssignment = new UserRoleAssignment();
                $userRoleAssignment->setUser($user);
                $userRoleAssignment->setRole($role);
                $entityManager->persist($userRoleAssignment);
            }
        }

        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Record updated'), null, Builder::SUCCESS);
    }

    public function delete($id, EntityManagerInterface $entityManager)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
            return;
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Record deleted'), null, Builder::SUCCESS);
    }

    public function updateProfile($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::WARNING);
        }

        $form = $formBuilder->build($user);

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
        }

        $postData = $form->getHttpData();

        $user->setUsername($postData['username']);
        $user->setFirstName($postData['firstName']);
        $user->setLastName($postData['lastName']);
        $user->setEmail($postData['email']);
        $user->setGender($postData['gender']);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Profile updated'), null, Builder::SUCCESS);
    }

    public function changePassword($id, EntityManagerInterface $entityManager, UserChangePassword $changePasswordForm)
    {
        /** @var $user EntityUser */
        if (!$user = $entityManager->find(EntityUser::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::DANGER);
        }

        $form = $changePasswordForm->generate($user);
        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        $postData = $form->getHttpData();

        if (!password_verify($postData['oldPassword'], $user->getPassword())) {
            $this->messageBag->pushFlashMessage($this->translator->_('Wrong old password'), null, Builder::DANGER);
            return;
        }

        $user->setPassword(password_hash($postData['password'], PASSWORD_BCRYPT));
        $entityManager->persist($user);
        $entityManager->flush();

        $this->redirect('/admin/auth/logout');
    }

    public function uploadProfileImage(EntityManagerInterface $entityManager)
    {
        $user = $this->session->get('user');
        if (!$user = $entityManager->find(EntityUser::class, $user['id'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, something is wrong'), null, Builder::DANGER);
        }

        $avatarHelper = new Avatar($entityManager);
        $uploadedFiles = $this->request->getUploadedFiles();
        $user = $avatarHelper->uploadAvatar(array_pop($uploadedFiles), $user);

        $this->session->unset('user');
        $this->session->set('user', [
            'id' => $user->getId(),
            'name' => $user->getDisplayName(),
            'avatar' => $avatarHelper->getUserAvatar($user)
        ]);

        $this->messageBag->pushFlashMessage($this->translator->_('Avatar uploaded!'), null, Builder::SUCCESS);

        return ['avatar' => $avatarHelper->getUserAvatar($user)];
    }

}
