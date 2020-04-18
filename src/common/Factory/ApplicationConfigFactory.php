<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Factory;

use Vemid\ProjectOne\Common\Config\ApplicationConfig;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\ZendConfigProvider;

/**
 * Class ApplicationConfigFactory
 * @package Library\Config
 */
class ApplicationConfigFactory
{
    /**
     * @return ConfigInterface
     */
    public static function create(): ConfigInterface
    {
        $config = new ConfigAggregator([
            new ZendConfigProvider(sprintf('%s/config/*.conf.{json,yaml,php}', APP_PATH))
        ]);

        return new ApplicationConfig($config->getMergedConfig());
    }
}
