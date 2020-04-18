<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Config;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    /**
     * @param string $id
     * @return ConfigInterface|string
     *
     * @throws ConfigNotFoundException
     */
    public function get($id);

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool;

    /**
     * @return array
     */
    public function toArray(): array;
}
