<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Laminas\I18n\Translator\Loader\PhpArray;
use Laminas\I18n\Translator\Translator;
use Vemid\ProjectOne\Common\Config\ConfigInterface;

/**
 * Class TranslatorFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class TranslatorFactory
{
    public function create(ConfigInterface $config)
    {
        return $this($config);
    }

    public function __invoke(ConfigInterface $config): Translator
    {
        $files = [];
        $languages = $config->get('language')->toArray();
        if (count($languages) === 0) {
            throw new \LogicException('Setup you language and locale in default config!');
        }

        foreach ($languages as $language) {
            $files['type'] = PhpArray::class;
            $files['filename'] = sprintf('%s/languages/%s.php', APP_PATH, $language);
        }

        return Translator::factory([
            'locale' => [array_values($languages)],
            'translation_files' => [$files],
            'event_manager_enabled' => true,
        ]);
    }
}
