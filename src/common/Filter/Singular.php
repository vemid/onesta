<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Filter;

/**
 * Class Singular
 * @package Vemid\ProjectOne\Common\Filter
 */
class Singular implements FilterInterface
{
    protected $_rules = array(
        '/(matr)ices$/' => '\1ix',
        '/(vert|ind)ices$/' => '\1ex',
        '/^(ox)en/' => '\1',
        '/(alias)es$/' => '\1',
        '/(cris|ax|test)es$/' => '\1is',
        '/(shoe)s$/' => '\1',
        '/(business)$/' => '\1',
        '/(o)es$/' => '\1',
        '/(bus|campus)es$/' => '\1',
        '/([m|l])ice$/' => '\1ouse',
        '/(x|ch|ss|sh)es$/' => '\1',
        '/(m)ovies$/' => '\1\2ovie',
        '/(s)eries$/' => '\1\2eries',
        '/([^aeiouy]|qu)ies$/' => '\1y',
        '/([lr])ves$/' => '\1f',
        '/(tive)s$/' => '\1',
        '/(hive)s$/' => '\1',
        '/([^f])ves$/' => '\1fe',
        '/(^analy)ses$/' => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
        '/([ti])a$/' => '\1um',
        '/(p)eople$/' => '\1\2erson',
        '/(m)en$/' => '\1an',
        '/(s)tatuses$/' => '\1\2tatus',
        '/(c)hildren$/' => '\1\2hild',
        '/(n)ews$/' => '\1\2ews',
        '/([^u])s$/' => '\1',
    );

    /**
     * @param $value
     * @return mixed|string|string[]|null
     */
    public function filter($value)
    {
        $result = strval($value);

        foreach ($this->_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);
                break;
            }
        }

        return $result;
    }
}
