<?php

namespace Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver;

use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Strategy interface for resolving parameters into argument values in the context of a given request.
 * @package Arbor\SIS\Application\Controller\ArgumentResolver
 */
interface ArgumentResolver
{
    /**
     * Whether the given parameter is supported by this resolver.
     *
     * @param ReflectionParameter $parameter The reflection parameter to check.
     * @return bool
     */
    public function supports(ReflectionParameter $parameter): bool;

    /**
     * Resolves a method parameter into an argument value from a given request.
     *
     * @param ReflectionParameter $parameter The reflection parameter to resolve. This parameter must
     *                                       have previously been passed to supports method which must
     *                                       have returned true.
     * @return mixed
     */
    public function resolve(ReflectionParameter $parameter);
}
