<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Psr\Http\Server\MiddlewareInterface;

/**
 * Interface MiddlewareAclInterface
 * @package Vemid\ProjectOne\Common\Middleware
 */
interface MiddlewareAclInterface extends MiddlewareInterface
{
    /**
     * @param array $roles
     * @param string $resourceId
     * @return bool
     */
    public function isAllowedWithRoles(array $roles, $resourceId = ''): bool;
}
