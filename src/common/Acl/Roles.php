<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Acl;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\ProjectOne\Entity\Entity\Role;
use Vemid\ProjectOne\Entity\Entity\User;

/**
 * Class Roles
 * @package Vemid\ProjectOne\Common\Acl
 */
class Roles implements RolesInterface
{

    /** @var ConfigInterface */
    private $config;

    /** @var EntityManagerInterface */
    private $entityManager;

    const GUEST = 'GUEST';

    const ADMIN = 'ADMIN';

    /**
     * Roles constructor.
     * @param ConfigInterface $config
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ConfigInterface $config, EntityManagerInterface $entityManager)
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getResources(): array
    {
        return $this->config->get('acl')->get('resources')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessPermissions(): array
    {
        return $this->config->get('acl')->get('assignments')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        return $this->entityManager->getRepository(Role::class)->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getUserRoles(int $id): array
    {
        /** @var User $user */
        if (!$user = $this->entityManager->find(User::class, $id)) {
            return [self::GUEST];
        }

        $roles = [];
        foreach ($user->getRoles() as $role) {
            $roles[] = $role->getCode();
        }

        return $roles;
    }
}
