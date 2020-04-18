<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\Common\EventManager;
use Vemid\ProjectOne\Common\Entity\Event\Subscriber\LogActivitySubscriber;

/**
 * Class EventManagerFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class EventManagerFactory
{
    /**
     * @return EventManager
     */
    public function create()
    {
        $eventManager = new EventManager();
        $eventManager->addEventSubscriber(new LogActivitySubscriber());

        return $eventManager;
    }
}
