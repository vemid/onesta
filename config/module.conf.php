<?php

use Vemid\ProjectOne\Main\ConfigProvider as DefaultConfigProvider;
use \Vemid\ProjectOne\Admin\ConfigProvider as AdminConfigProvider;
use \Vemid\ProjectOne\Api\ConfigProvider as ApiConfigProvider;
use \Vemid\ProjectOne\Form\ConfigProvider as FormConfigProvider;

return [
    'modules' => [
        'default' => AdminConfigProvider::class,
        'api' => ApiConfigProvider::class,
        'form' => FormConfigProvider::class,
    ]
];
