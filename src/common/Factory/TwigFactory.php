<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Permissions\Acl\AclInterface;
use Mezzio\Twig\TwigExtension;
use Mezzio\Twig\TwigRenderer;
use Odan\Twig\TwigAssetsExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\TwigFunction;
use \Psr\Container\NotFoundExceptionInterface;
use \Psr\Container\ContainerExceptionInterface;
use Vemid\ProjectOne\Common\Acl\Roles;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\ProjectOne\Common\Config\ConfigResolvedInterface;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Common\Translator\TranslationInterface;

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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var AclInterface */
    private $acl;

    /** @var ConfigInterface */
    private $configObject;

    /**
     * @param ContainerInterface $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->environment = $container->get(Environment::class);
        $this->extension = $container->get(TwigExtension::class);
        $this->config = $container->get(ConfigResolvedInterface::class);
        $this->translator = $container->get(TranslationInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->acl = $container->get(AclInterface::class);
        $this->configObject = $container->get(ConfigInterface::class);
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
        $acl = $this->acl;
        $roleManager = new Roles($this->configObject, $this->entityManager);

        $functionTranslate = new TwigFunction('t', static function ($value) use ($translator) {
            return $translator->translate($value);
        });

        $functionIsAllowed = new TwigFunction('isAllowed', static function ($id, $resource) use ($roleManager, $acl) {
            $route = preg_replace('/\/\d*$/', '', $resource);

            foreach ($roleManager->getUserRoles($id) as $role) {
                if ($acl->isAllowed($role, $route)) {
                    return true;
                }
            }

            return false;
        });

        $this->environment->addExtension($this->extension);
        $this->environment->addExtension(new TwigAssetsExtension($this->environment, $this->config['templates']['external']));
        $this->environment->addFunction($functionCipher);
        $this->environment->addFunction($functionIsAllowed);
        $this->environment->addFunction($functionTranslate);

        return new TwigRenderer($this->environment);
    }
}
