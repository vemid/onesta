<?php

namespace Vemid\ProjectOne\Common\Route\Handler\ArgumentResolver;

use Vemid\ProjectOne\Common\Route\AbstractHandler;
use ReflectionObject;
use RuntimeException;

class ArgumentResolverManager
{
    private $resolvers;

    public function __construct(ArgumentResolver ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(AbstractHandler $handler, string $method) : array
    {
        $args = [];

        if (!method_exists($handler,  $method))  {
            return $args;
        }

        $reflectionObject = new ReflectionObject($handler);
        $reflectionMethod = $reflectionObject->getMethod($method);

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $value = $reflectionParameter->isDefaultValueAvailable() ?
                $reflectionParameter->getDefaultValue() : null;

            foreach ($this->resolvers as $resolver) {
                if ($resolver->supports($reflectionParameter)) {
                    $value = $resolver->resolve($reflectionParameter);
                    break;
                }
            }

            if (null === $value && !$reflectionParameter->allowsNull()) {
                throw new RuntimeException(sprintf('%s parameter cannot be null', $reflectionParameter->getName()));
            }

            $args[] = $value;
        }

        return $args;
    }
}
