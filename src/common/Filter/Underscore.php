<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Filter;

/**
 * Class Underscore
 * @package Vemid\ProjectOne\Common\Filter
 */
class Underscore implements FilterInterface
{
    protected $_rules = array(
        '/([a-z])([A-Z])/' => '\1_\2',
        '/[\s-]+/' => '_',
    );

    /**
     * Takes multiple words separated by spaces or underscores and camelizes them
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $result = strval($value);

        foreach ($this->_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);
            }
        }

        return strtolower($result);
    }
}
