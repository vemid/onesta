<?php

declare(strict_types=1);

use GO\Scheduler;

require_once __DIR__ . '/../init.php';

$console = __DIR__ . '/../bin/console.php';

$scheduler = new Scheduler();
//$scheduler->php(sprintf('%s test:email', $console))->everyMinute();
$scheduler->run();
