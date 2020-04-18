<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Misc;

use OnlineCity\Encoder\GsmEncoder;
use OnlineCity\SMPP\SMPP;
use OnlineCity\SMPP\SmppAddress;
use OnlineCity\SMPP\SmppClient;
use OnlineCity\Transport\SocketTransport;

/**
 * Class SendSms
 * @package Vemid\ProjectOne\Common\Misc
 */
class SendSms
{
    /** @var array */
    private $config;

    /** @var SmppClient */
    private $smpp;

    /**
     * SendSms constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->smpp = $this->buildSmpp();
    }

    /**
     * @param $to
     * @param string $message
     */
    public function sendSms($to, string $message)
    {
        $encodedMessage = GsmEncoder::utf8_to_gsm0338($message);
        $from = new SmppAddress($this->config['sender']['name'], SMPP::TON_NATIONAL);

        if (!is_array($to)) {
            $to = [$to];
        }

        foreach ($to as $key => $number) {
            $to = new SmppAddress($number, SMPP::TON_NATIONAL, SMPP::NPI_NATIONAL);
            $messageId = (int)$this->smpp->sendSMS($from, $to, $encodedMessage);
            sleep(6);
        }

        $this->smpp->close();
    }

    /**
     * @return SmppClient
     */
    private function buildSmpp()
    {
        $transport = new SocketTransport($this->config['connection']['host'], $this->config['connection']['port']);
        $transport->setRecvTimeout($this->config['sender']['recvTimeout']);
        $transport->setSendTimeout($this->config['sender']['sendTimeout']);
        $transport->open();

        $smpp = new SmppClient($transport);
        SmppClient::$sms_null_terminate_octetstrings = false;
        SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
        SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;

        $smpp->bindTransmitter($this->config['connection']['login'], $this->config['connection']['password']);

        return $smpp;
    }
}
