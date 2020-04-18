<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Translator;

use Laminas\I18n\Translator\TranslatorInterface;

/**
 * Class Translation
 * @package Vemid\ProjectOne\Common\Translator
 */
class Translation implements TranslationInterface
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * Translation constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function translate($message): string
    {
        return $this->translator->translate($message, 'default', \Locale::getDefault());
    }

    /**
     * @inheritDoc
     */
    public function translatePlural($singular, $plural, $number): string
    {
        return $this->translator->translatePlural($singular, $plural, $number, 'default', \Locale::getDefault());
    }

    /**
     * @inheritDoc
     */
    public function _($message): string
    {
        return $this->translate($message);
    }
}
