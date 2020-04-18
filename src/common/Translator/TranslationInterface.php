<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Translator;

/**
 * Interface TranslationInterface
 * @package Vemid\ProjectOne\Common\Translator
 */
interface TranslationInterface
{
    /**
     * @param $message
     * @return string
     */
    public function translate($message): string;

    /**
     * @param $singular
     * @param $plural
     * @param $number
     * @return string
     */
    public function translatePlural($singular, $plural, $number): string;

    /**
     * @param $message
     * @return string
     */
    public function _($message): string;
}
