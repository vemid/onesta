<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Exception;

/**
 * Interface ProvidesHttpStatusCodeInterface
 * @package Vemid\ProjectOne\Common\Exception
 */
interface ProvidesHttpStatusCodeInterface
{
    public function getHttpStatusCode() : int;
}
