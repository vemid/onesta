<?php

use Vemid\ProjectOne\Common\Application\Cli;

define('APP_PATH', realpath(__DIR__) . '/..');
require_once realpath(__DIR__) . '/../vendor/autoload.php';

$cli = new Cli();

$container = $cli->getDi();