<?php

namespace Vemid\ProjectOne\Common\Route\Handler\Exception;

/**
 * Class RuntimeException
 * @package Vemid\ProjectOne\Common\Route\Handler\Exception
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @param string $username
     * @return RuntimeException
     */
    public static function integratorNotFound(string $username): self
    {
        return new self(sprintf('Integrator with the username %s not found in database', $username));
    }
}