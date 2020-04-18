<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Event;

use League\Event\ListenerInterface;
use \ReflectionClass;

/**
 * Class EventList
 * @package Vemid\ProjectOne\Common\Event
 */
class EventList implements EventListInterface
{
    /** @var string */
    public const EVENT_BEFORE_EMAIL_SENT = 'event.before.email.sent';
    public const EVENT_AFTER_EMAIL_SENT = 'event.after.email.sent';


    /**
     * {@inheritDoc}
     * @throws \ReflectionException
     */
    public function __invoke(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }

    /**
     * {@inheritDoc}
     */
    public function resolveHandler(string $event): ListenerInterface
    {
        $composedEventName = explode('.', $event);

        if (count($composedEventName) < 2) {
            throw new \LogicException(sprintf(
                'Event name format is wrong! Valid format is: event.%s',
                (!$event || $event === 'event' ? 'eventName' : $event)
            ));
        }

        $prefixEvent = array_shift($composedEventName);
        if (!$prefixEvent || $prefixEvent !== 'event') {
            throw new \LogicException(sprintf(
                'Event name format is wrong! Valid format is: event.%s',
                (!$event || $event === 'event' ? 'eventName' : $event)
            ));
        }

        $className = implode('', array_map('ucfirst', $composedEventName));

        $class = "\\Vemid\\ProjectOne\\Common\\Event\\Listeners\\$className";

        if (!class_exists($class)) {
            throw new \LogicException(sprintf('Handler class %s do not exist.', $class));
        }

        return new $class;
    }
}
