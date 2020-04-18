<?php

declare(strict_types=1);

use OnlineCity\Encoder\GsmEncoder;
use OnlineCity\SMPP\SMPP;
use OnlineCity\SMPP\SmppAddress;
use OnlineCity\SMPP\SmppClient;
use OnlineCity\SMPP\Unit\SmppSms;
use OnlineCity\Transport\SocketTransport;

require_once __DIR__ . '/../init.php';

$transport = new SocketTransport(array('bulk.mobile-gw.com'), 7900);
$transport->setRecvTimeout(60000);
$transport->setSendTimeout(60000);
$transport->open();

SmppClient::$sms_null_terminate_octetstrings = false;
SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_IDN;

$smpp = new SmppClient($transport);
$smpp->bindTransmitter("bebaapi", "dKkvWMoH");

$message = 'Happy';
$encodedMessage = GsmEncoder::utf8_to_gsm0338($message);
$from = new SmppAddress('Onesta', SMPP::TON_NATIONAL);
$to = new SmppAddress(381692002521, SMPP::TON_NATIONAL, SMPP::NPI_NATIONAL);

$messageId = $smpp->sendSMS($from, $to, $encodedMessage);
$smpp->close();
var_dump($messageId);

SmppClient::$sms_null_terminate_octetstrings = false;
SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_IDN;

$transport = new SocketTransport(array('bulk.mobile-gw.com'), 7900);
$transport->setRecvTimeout(60000);
$transport->setSendTimeout(60000);
$transport->open();

$smpp = new SmppClient($transport);
$smpp->bindReceiver("bebaapi", "dKkvWMoH");
$sms = $smpp->readSMS();

$smpp->close();


// Construct transport and client
//SmppClient::$system_type = '901039';
//SmppClient::$sms_null_terminate_octetstrings = false;
//SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
//SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;
//
//$transport = new SocketTransport(array('bulk.mobile-gw.com'),7900);
//$transport->setRecvTimeout(60000);
//$smpp = new SmppClient($transport);
//
////$smpp->debug = true;
////$transport->debug = true;
//
//// Open the connection
//$transport->open();
//$smpp->bindReceiver("bebaapi","dKkvWMoH");
//
//$i= 0;
//while ($i < 210) {
//    sleep(6);
//    /** @var SmppSms $sms */
//    $sms = $smpp->queryStatus();
//    echo "SMS:\n";
//    var_dump($sms->message);
//    $i++;
//}


// Close connection
$smpp->close();

//$test = new SmsMessage()
//darkovesic/php-smpp-worker
