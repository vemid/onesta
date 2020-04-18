<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Console\Loader;

require_once __DIR__ . '/../init.php';

$app = new Loader('1.0.0');
$app->run();
