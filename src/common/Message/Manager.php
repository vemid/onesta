<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Message;

/**
 * Class Manager
 * @package Vemid\ProjectOne\Common\Message
 */
class Manager implements MessageInterface
{
    /** @var array */
    protected $messages = [];

    /**
     * {@inheritDoc}
     */
    public function pushMessage(Builder $message): Manager
    {
        $this->messages[$message->getType()][] = $message;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessages($type = null): array
    {
        if ($type === null) {
            $messages = [];
            foreach ($this->messages as $messageGroup) {
                $messages += $messageGroup;
            }

            return $messages;
        }

        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }

        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function hasMessages($type): bool
    {
        if (array_key_exists($type, $this->messages)) {
            return count($this->messages[$type]) > 0;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray($type = null): array
    {
        $array = [];
        foreach ($this->getMessages($type) as $message) {
            $array[] = $message->toArray();
        }

        return $array;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): Manager
    {
        $this->messages = [];

        return $this;
    }
}
