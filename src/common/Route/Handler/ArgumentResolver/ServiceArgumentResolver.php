<?php

namespace Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver;

use Psr\Container\ContainerInterface;
use ReflectionParameter;

/**
 * Class ServiceArgumentResolver
 * @package Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver
 */
final class ServiceArgumentResolver implements ArgumentResolver
{
    private $container;

    /**
     * ServiceArgumentResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return string
     */
    protected function getServiceId(ReflectionParameter $parameter): string
    {
        if ($parameter->hasType() && !$parameter->getType()->isBuiltin()) {
            return $parameter->getType()->getName();
        }

        return $parameter->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ReflectionParameter $parameter): bool
    {
        if (!$parameter->hasType() || $parameter->getType()->isBuiltin()) {
            return false;
        }

        return $this->container->has($this->getServiceId($parameter));
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(ReflectionParameter $parameter)
    {
        return $this->container->get($this->getServiceId($parameter));
    }
}
