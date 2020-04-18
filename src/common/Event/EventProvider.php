<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Event;

use League\Event\ListenerAcceptorInterface;
use League\Event\ListenerProviderInterface;

/**
 * Class EventProvider
 * @package Vemid\ProjectOne\Common\Event
 */
class EventProvider implements ListenerProviderInterface
{
    /** @var EventListInterface */
    private $eventList;

    /**
     * EventProvider constructor.
     * @param EventListInterface $eventList
     */
    public function __construct(EventListInterface $eventList)
    {
        $this->eventList = $eventList;
    }

    /**
     * @param ListenerAcceptorInterface $listenerAcceptor
     * @return ListenerAcceptorInterface
     */
    public function provideListeners(ListenerAcceptorInterface $listenerAcceptor): ListenerAcceptorInterface
    {
        $eventList = ($this->eventList)();
        foreach ($eventList as $event) {
            $listenerAcceptor->addListener($event, $this->eventList->resolveHandler($event));
        }

        return $listenerAcceptor;
    }
}
