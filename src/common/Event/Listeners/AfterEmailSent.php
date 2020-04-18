<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Event\Listeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;

/**
 * Class AfterEmailSent
 * @package Vemid\ProjectOne\Common\Event\Listeners
 */
class AfterEmailSent extends AbstractListener
{
    /**
     * {@inheritDoc}
     */
    public function handle(EventInterface $event, ...$params): void
    {
    }
}
