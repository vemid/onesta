<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Message;

/**
 * Interface MessageInterface
 * @package Vemid\ProjectOne\Common\Message
 */
interface MessageInterface
{
    /**
     * @param Builder $message
     * @return Manager
     */
    public function pushMessage(Builder $message): Manager;

    /**
     * @param null|string $type
     * @return array
     */
    public function getMessages($type = null): array;

    /**
     * @param $type
     * @return bool
     */
    public function hasMessages($type): bool;

    /**
     * @param null|string $type
     * @return array
     */
    public function toArray($type = null): array;

    /**
     * @return Manager
     */
    public function clear(): Manager;
}
