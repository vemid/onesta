<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Config;

use \ArrayIterator;

/**
 * Class ApplicationConfig
 * @package Library\Config
 */
class ApplicationConfig extends ArrayIterator implements ConfigInterface
{
    /** @var array */
    private $config;

    /**
     * ApplicationConfig constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;

        parent::__construct($config);
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        $config = $this->resolveKey($id)->toArray();

        if (!$this->has($id)) {
            throw ConfigNotFoundException::forIndex($id);
        }

        if (array_key_exists($id, $config)) {
            $value = $config[$id];
        } else {
            $value = $this[$id];
        }

        if (is_array($value)) {
             return new self($value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function has($id): bool
    {
        return array_key_exists($id, $this->toArray()) || array_key_exists($id, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $config = [];
        foreach ($this->config as $configKey => $configValue) {
            if (is_string($configKey) && preg_match('/(.*?)\.{1}(.*)/', $configKey, $matches)) {
                $newConfig = new self([$matches[2] => $configValue]);
                if (!isset($config[$matches[1]])) {
                    $config[$matches[1]] = $newConfig->toArray();
                } else {
                    $config[$matches[1]] = array_merge($config[$matches[1]], $newConfig->toArray());
                }
            } else {
                $config[$configKey] = $configValue;
            }
        }

        return $config;
    }

    /**
     * @param $id
     * @return ApplicationConfig
     */
    private function resolveKey($id): self
    {
        if (array_key_exists($id, $this->config)) {
            return new self([$id => $this->config[$id]]);
        }

        $config = [];
        foreach ($this->config as $key => $item) {
           if (strpos($key, $id) !== false && preg_match('/\.{1}(.*)/', $key, $matches)) {
                $config[$id][$matches[1]] = $item;
            }
        }

        if (!count($config) && preg_match('/\.{1}(.*)/', $id, $matches)) {
            $composedKey = explode('.', $id);
            if (array_key_exists($composedKey[0], $this->config) && array_key_exists($composedKey[1], $this->config[$composedKey[0]])) {
                return new self([$id => $this->config[$composedKey[0]][$composedKey[1]]]);
            }
        }

        return new self($config);
    }
}
