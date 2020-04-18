<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Acl;

/**
 * Interface RolesInterface
 * @package Vemid\ProjectOne\Common\Acl
 */
interface RolesInterface
{

    /**
     * @return array
     */
    public function getRoles(): array;

    /**
     * @param int $userId
     * @return array
     */
    public function getUserRoles(int $userId): array;

    /**
     * @return array
     */
    public function getResources(): array;

    /**
     * @return array
     */
    public function getAccessPermissions(): array;
}
