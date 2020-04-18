<?php

declare(strict_types=1);

use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\SmppWorker\QueueModel;
use Vemid\SmppWorker\SmsFactory;
use Vemid\SmppWorker\SmsMessage;

require_once __DIR__ . '/../init.php';

$config = $container->get(ConfigInterface::class);
$options = $config->get('sms')->toArray();

$q = new QueueModel($options);

$m = array();
for ($n=0;$n<2;$n++) {
    $r = array();
    for($i=0;$i<2;$i++) {
        $r[] = 381692002521;
    }

    $m[] = new SmsMessage(1234, 'Test', 'Lorem ipsum', $r);
}

$q->produce($m);

function debug($string) {
    list($microtime, $time) = explode(' ',microtime());
    $prefix = sprintf("%s.%02.2s P:%05s ",date('Y-m-d\TH:i:s'),(int) ($microtime*1000000),getmypid());
    $s = $prefix.str_replace("\n","\n".$prefix."\t",trim($string))."\n";
    file_put_contents('worker.log',$s,FILE_APPEND);
}

function protocolDebug($string) {
    list($microtime, $time) = explode(' ',microtime());
    $prefix = sprintf("%s.%02.2s P:%05s ",date('Y-m-d\TH:i:s'),(int) ($microtime*1000000),getmypid());
    $s = $prefix.str_replace("\n","\n".$prefix."\t",trim($string))."\n";
    file_put_contents('trace.log',$s,FILE_APPEND);
}

$f = new SmsFactory($options);
$f->startAll();