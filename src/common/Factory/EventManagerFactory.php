<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Events;
use Vemid\ProjectOne\Common\Entity\Event\Listener\InsertProductSupplierAfterReceiptItemAdded;
use Vemid\ProjectOne\Common\Entity\Event\Subscriber\LogActivitySubscriber;
use Vemid\ProjectOne\Common\Entity\Event\Subscriber\SetReferencedObjectField;

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
        $eventManager->addEventListener([Events::postPersist, Events::postRemove], new InsertProductSupplierAfterReceiptItemAdded());
        $eventManager->addEventSubscriber(new LogActivitySubscriber());
        $eventManager->addEventSubscriber(new SetReferencedObjectField());

        return $eventManager;
    }
}
