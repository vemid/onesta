<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Route;

/**
 * Interface HandlerInterface
 * @package Vemid\ProjectOne\Common\Route
 */
interface HandlerInterface
{
    public function handle(...$arguments);
}