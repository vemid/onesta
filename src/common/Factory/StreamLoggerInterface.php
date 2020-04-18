<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface StreamLoggerInterface
 * @package Vemid\ProjectOne\Common\Factory
 */
interface StreamLoggerInterface
{
    /**
     * @param ConfigInterface $config
     * @return LoggerInterface
     */
    public function create(ConfigInterface $config): LoggerInterface;

    /**
     * @param ConfigInterface $config
     * @return LoggerInterface
     */
    public function __invoke(ConfigInterface $config): LoggerInterface;
}
