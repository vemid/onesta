<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Middleware;

use Laminas\Permissions\Acl\AclInterface;
use Laminas\Permissions\Acl\Resource\GenericResource;
use Laminas\Permissions\Acl\Role\GenericRole;
use Mezzio\Session\SessionInterface;
use Mezzio\Template\TemplateRendererInterface;
use Middlewares\Utils\Traits\HasResponseFactory;
use Vemid\ProjectOne\Common\Acl\Roles;
use Vemid\ProjectOne\Common\Acl\RolesInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Acl
 * @package Vemid\ProjectOne\Common\Middleware
 */
class Acl implements MiddlewareAclInterface
{
    use HasResponseFactory;

    /** @var RolesInterface */
    protected $roleManager;

    /** @var TemplateRendererInterface */
    protected $templateRenderer;

    /** @var array */
    protected $whiteList;

    /** @var */
    protected $handler;

    /** @var AclInterface  */
    protected $acl;

    /**
     * Acl constructor.
     * @param RolesInterface $roleManager
     * @param AclInterface $acl
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(RolesInterface $roleManager, AclInterface $acl, TemplateRendererInterface $templateRenderer)
    {
        $this->roleManager = $roleManager;
        $this->templateRenderer = $templateRenderer;
        $this->acl = $acl;
        $this->whiteList = [];

        foreach ($roleManager->getResources() as $resourceId) {
            $resource = new GenericResource($resourceId);
            if ($this->acl->hasResource($resource)) {
                continue;
            }

            $this->acl->addResource($resource);
        }

        foreach ($roleManager->getRoles() as $role) {
            $this->acl->addRole(new GenericRole($role->getCode()));
        }

        foreach ($roleManager->getAccessPermissions() as $role => $resources) {
            if ($resources === '*') {
                $this->acl->allow($role);
                continue;
            }

            foreach ($resources as $resource) {
                $this->acl->allow($role, $resource);
            }
        }
    }

    /**
     * @param array $roles
     * @param string $resourceId
     * @return bool
     */
    public function isAllowedWithRoles(array $roles, $resourceId = ''): bool
    {
        foreach ($roles as $role) {
            if ($this->acl->isAllowed($role, $resourceId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $allowed = false;
        $route = preg_replace('/\/\d*$/', '', $request->getUri()->getPath());
        /** @var SessionInterface $session */
        $session = $request->getAttribute('session');

        if (!$session || !$session->has('user')) {
            $roles = [Roles::GUEST];
        } else {
            $roles = $this->roleManager->getUserRoles($session->get('user')['id']);
        }

        $fn = function (ServerRequestInterface $requestInterface) {
            $route = $requestInterface->getAttribute('route');
            if (!empty($route)) {
                foreach ($this->acl->getRoles() as $role) {
                    if ($this->acl->isAllowed($role, $route->getPattern())) {
                        return true;
                    }
                }
            }

            return false;
        };

        foreach ($this->whiteList as $whiteUri) {
            if (strpos($route, $whiteUri) !== false) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            try {
                $allowed = $this->isAllowedWithRoles($roles, $route);
            } catch (\InvalidArgumentException $iae) {
                $allowed = $fn($request);
            }
        }

        if (!$allowed) {
            $isAjax = 'XMLHttpRequest' === ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');

            $response = $this->createResponse(403);

            if (!$isAjax) {
                $body = $this->templateRenderer->render('error::403.html.twig');
                $response->getBody()->write((string)trim(preg_replace('/\s\s+/', ' ', $body)));
            }

            return $response;
        }

        return $handler->handle($request);
    }
}
