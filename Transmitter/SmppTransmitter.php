<?php

namespace Kronas\SmppClientBundle\Transmitter;

use Kronas\SmppClientBundle\Encoder\GsmEncoder;
use Kronas\SmppClientBundle\SMPP;
use Kronas\SmppClientBundle\SmppCore\SmppAddress;
use Kronas\SmppClientBundle\SmppCore\SmppClient;
use Kronas\SmppClientBundle\Transport\SocketTransport;
use Kronas\SmppClientBundle\Transport\TransportInterface;

/**
 * Class SmppWrapper.
 */
class SmppTransmitter
{
    private $transportParamters;
    private $login;
    private $password;
    private $signature;
    private $systemType;
    private $debug;

    /** @var TransportInterface */
    private $transport;
    /** @var SmppClient */
    private $smpp;

    /**
     * @param array  $transportParamters
     * @param string $login
     * @param string $password
     * @param string $signature
     * @param string $systemType
     * @param array  $debug
     */
    public function __construct(array $transportParamters, $login, $password, $signature, $systemType, array $debug)
    {
        $this->transportParamters = $transportParamters;
        $this->login = $login;
        $this->password = $password;
        $this->signature = $signature;
        $this->systemType = $systemType;
        $this->debug = $debug;
    }

    /**
     * @param string $to
     * @param string $message
     *
     * @return string
     */
    public function send($to, $message)
    {
        $message = GsmEncoder::utf8_to_gsm0338($message);
        $from = new SmppAddress($this->signature, SMPP::TON_ALPHANUMERIC);
        $to = new SmppAddress(intval($to), SMPP::TON_INTERNATIONAL, SMPP::NPI_E164);

        $this->openSmppConnection();
        $messageId = $this->smpp->sendSMS($from, $to, $message);
        $this->closeSmppConnection();

        return $messageId;
    }

    private function openSmppConnection()
    {
        $this->transport = new SocketTransport($this->transportParamters[0], $this->transportParamters[1]);
        $this->transport->setSendTimeout($this->transportParamters[2]);

        $this->smpp = new SmppClient($this->transport);

        $this->transport->debug = $this->debug['transport'];
        $this->smpp->debug = $this->debug['smpp'];

        $this->transport->open();
        $this->smpp->bindTransmitter($this->login, $this->password, $this->systemType);
    }

    private function closeSmppConnection()
    {
        $this->smpp->close();
        $this->transport->close();
    }
}
