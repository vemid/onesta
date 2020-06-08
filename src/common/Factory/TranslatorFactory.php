<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Laminas\Cache\Storage\Adapter\Apcu;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Cache\StorageFactory;
use Laminas\I18n\Translator\Loader\PhpArray;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use Vemid\ProjectOne\Common\Config\ConfigInterface;

/**
 * Class TranslatorFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class TranslatorFactory
{
    /**
     * @param ConfigInterface $config
     * @return TranslatorInterface
     */
    public function create(ConfigInterface $config)
    {
        return $this($config);
    }

    /**
     * @param ConfigInterface $config
     * @return Translator
     */
    public function __invoke(ConfigInterface $config)
    {
        $files = [];
        $languages = $config->get('language')->toArray();
        foreach ($languages as $language) {
            $files['type'] = PhpArray::class;
            $files['filename'] = sprintf('%s/languages/%s.php', APP_PATH, $language);
        }



        $translator = Translator::factory([
            'locale' => [array_values($languages)],
            'translation_files' => [$files],
            'event_manager_enabled' => true,
        ]);

        if ($config->get('environment') !== 'development') {
            $cache = StorageFactory::factory([
                'adapter' => [
                    'name'    => Apcu::class,
                ],
            ]);

            $translator->setCache($cache);
        }

        return $translator;
    }
}
