<?php

use Vemid\ProjectOne\Common\Mailer\MailManager;
use Vemid\ProjectOne\Common\Mailer\MailManagerInterface;

require_once __DIR__ . '/../init.php';

/** @var MailManager $mailManager */
$mailManager = $container->get(MailManagerInterface::class);
$message = $mailManager->createMessageFromView('email::welcome');
$message->setTo('darko.vesic@arbor-education.com');
$message->setSubject('Forgot your password');
$message->setReplyTo('darko.vesic@arbor-education.com');

$sent =  $message->send();
$test = 0;
