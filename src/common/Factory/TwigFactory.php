<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Odan\Twig\TwigAssetsExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\TwigFunction;
use Vemid\ProjectOne\Common\Config\ConfigResolvedInterface;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Common\Translator\TranslationInterface;
use Zend\Expressive\Twig\TwigExtension;
use Zend\Expressive\Twig\TwigRenderer;

/**
 * Class TwigFactory
 * @package Vemid\ProjectOne\Common\Factory
 */
class TwigFactory
{
    /** @var Environment */
    private $environment;

    /** @var TwigExtension */
    private $extension;

    /** @var ConfigResolvedInterface */
    private $config;

    /** @var mixed|TranslationInterface  */
    private $translator;

    /**
     * TwigFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->environment = $container->get(Environment::class);
        $this->extension = $container->get(TwigExtension::class);
        $this->config = $container->get(ConfigResolvedInterface::class);
        $this->translator = $container->get(TranslationInterface::class);
    }

    /**
     * @return TwigRenderer
     */
    public function create(): TwigRenderer
    {
        $functionCipher = new TwigFunction('cipher', static function ($value) {
            return PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', $value);
        });

        $translator = $this->translator;

        $functionTranslate = new TwigFunction('t', static function ($value) use ($translator) {
            return $translator->translate($value);
        });

        $this->environment->addExtension($this->extension);
        $this->environment->addExtension(new TwigAssetsExtension($this->environment, $this->config['templates']['external']));
        $this->environment->addFunction($functionCipher);
        $this->environment->addFunction($functionTranslate);

        return new TwigRenderer($this->environment);
    }
}
