<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Config;

use Psr\Container\ContainerExceptionInterface;
use \RuntimeException;

/**
 * Class ContainerException
 */
class ConfigNotFoundException extends RuntimeException implements ContainerExceptionInterface
{
    /**
     * @param string $index
     * @return ConfigNotFoundException
     */
    public static function forIndex(string $index): self
    {
        return new self(sprintf("'%s' index not found in config files", $index));
    }
}
