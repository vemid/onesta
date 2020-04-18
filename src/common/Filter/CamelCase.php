<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Filter;

/**
 * Class CamelCase
 * @package Vemid\ProjectOne\Common\Filter
 */
class CamelCase implements FilterInterface
{

    /**
     * @param $value
     * @return false|mixed|string
     */
    public function filter($value)
    {
        $value = trim($value);

        if (!preg_match('/[\s_-]+/', $value)) {
            return $value;
        }

        $value = 'x' . strtolower($value);
        $value = ucwords(preg_replace('/[\s_-]+/', ' ', $value));

        return substr(str_replace(' ', '', $value), 1);
    }
}
