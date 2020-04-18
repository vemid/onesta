<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Misc\SendSms;

require_once __DIR__ . '/../init.php';

$message = 'Happy';

$numbers = [
    381692002521,
    381692002521,
];

$smsSender = new SendSms([]);
$smsSender->sendSms($numbers, $message);