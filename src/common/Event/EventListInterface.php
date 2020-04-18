<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Event;

use League\Event\ListenerInterface;
use \LogicException;

/**
 * Interface EventListInterface
 * @package Vemid\ProjectOne\Common\Event
 */
interface EventListInterface
{
    /**
     * @return array
     */
    public function __invoke(): array;

    /**
     * @param string $event
     * @return ListenerInterface
     * @throws LogicException
     */
    public function resolveHandler(string $event): ListenerInterface;
}
