<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigEnvironmentFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class TwigEnvironmentFactory
{
    /**
     * @param array $config
     * @return Environment
     * @throws \Twig\Error\LoaderError
     */
    public function __invoke(array $config)
    {
        $templateConfig = $config['templates'];
        if (isset($templateConfig['paths'])) {
            $loader = new FilesystemLoader($templateConfig['paths']['__main__']);
            unset($templateConfig['paths']['__main__']);

            $loader->addPath(APP_PATH . '/build', 'build');
            foreach ($templateConfig['paths'] as $namespace => $path) {
                $loader->addPath($path, $namespace);
            }

        } else {
            $loader = new ArrayLoader();
        }

        return new Environment($loader, [
            'cache' => $config['templates']['cache'] ? $config['templates']['cache_dir'] : false
        ]);
    }
}
