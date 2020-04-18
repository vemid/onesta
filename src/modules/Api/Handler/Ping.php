<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Api\Handler;

use Vemid\ProjectOne\Common\Route\AbstractHandler;

/**
 * Class Ping
 * @package Vemid\ProjectOne\Api\Handler
 */
class Ping extends AbstractHandler
{
    public function index()
    {
        return ['ping' => 'pong'];
    }
}