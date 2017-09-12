<?php

namespace Kronas\SmppClientBundle\Transmitter;

use Kronas\SmppClientBundle\Encoder\CyrillicEncoder;
use Kronas\SmppClientBundle\Encoder\EncoderInterface;
use Kronas\SmppClientBundle\Encoder\GsmEncoder;
use Kronas\SmppClientBundle\Exception\InvalidEncoderException;
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
    /**
     * @var array
     */
    private $hosts;
    /**
     * @var array
     */
    private $ports;
    /**
     * @var int
     */
    private $timeout;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $signature;
    /**
     * @var string
     */
    private $systemType;
    /**
     * @var array
     */
    private $debug;
    /**
     * @var array
     */
    private $encoders;

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var SmppClient
     */
    private $smpp;

    /**
     * @param array $hosts
     * @param array $ports
     * @param $timeout
     * @param string $login
     * @param string $password
     * @param string $signature
     * @param string $systemType
     * @param array $debug
     * @param EncoderInterface[] $encoders
     */
    public function __construct(array $hosts, array $ports, $timeout, $login, $password, $signature, $systemType, array $debug, array $encoders = [])
    {
        $this->hosts = $hosts;
        $this->ports = $ports;
        $this->timeout = $timeout;
        $this->login = $login;
        $this->password = $password;
        $this->signature = $signature;
        $this->systemType = $systemType;
        $this->debug = $debug;
        $this->addEncoders($encoders);
    }

    /**
     * @param string $to
     * @param string $message
     * @param string $encoderName
     * @param int    $dataCoding
     *
     * @return string
     */
    public function send($to, $message, $encoderName, $dataCoding)
    {
        /** @var EncoderInterface $encoder */
        $encoder = $this->encoders[$encoderName];
        $message = $encoder::encode($message);
        $from = new SmppAddress($this->signature, SMPP::TON_ALPHANUMERIC);
        $to = new SmppAddress(intval($to), SMPP::TON_INTERNATIONAL, SMPP::NPI_E164);

        $this->openSmppConnection();
        $messageId = $this->smpp->sendSMS($from, $to, $message, null, $dataCoding);
        $this->closeSmppConnection();

        return $messageId;
    }

    private function openSmppConnection()
    {
        $this->transport = new SocketTransport($this->hosts, $this->ports);
        $this->transport->setSendTimeout($this->timeout);

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

    private function addEncoders(array $encoders)
    {
        //Add default encoders
        $this->encoders = [
            'gsm' => GsmEncoder::class,
            'cyr' => CyrillicEncoder::class,
        ];

        //Add other encoders
        foreach ($encoders as $encoder) {
            if ($encoder instanceof EncoderInterface) {
                $this->encoders[] = $encoder;
            } else {
                throw new InvalidEncoderException('Encoders must imlement EncoderInterface.');
            }
        }
    }
}
