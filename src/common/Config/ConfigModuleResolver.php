<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Config;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ConfigModuleResolver
 * @package Vemid\ProjectOne\Common\Config
 */
class ConfigModuleResolver
{
    /** @var ConfigInterface */
    private $config;

    /** @var ServerRequestInterface */
    private $request;

    /**
     * ConfigModuleResolver constructor.
     * @param ConfigInterface $config
     * @param ServerRequestInterface $request
     */
    public function __construct(ConfigInterface $config, ServerRequestInterface $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    public function resolve()
    {
        $pathSplit = explode('/', ltrim($this->request->getUri()->getPath(), '/'));
        $path = array_shift($pathSplit);

        if (array_key_exists($path, $this->config['modules'])) {
            return (new $this->config['modules'][$path])();
        }

        return (new $this->config['modules']['default'])();
    }
}