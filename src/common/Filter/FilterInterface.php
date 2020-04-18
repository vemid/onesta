<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Filter;

/**
 * Interface FilterInterface
 * @package Vemid\ProjectOne\Common\Filter
 */
interface FilterInterface
{
    /**
     * @param $value
     * @return mixed
     */
    public function filter($value);
}
