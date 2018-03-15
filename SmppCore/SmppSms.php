<?php

namespace Kronas\SmppClientBundle\SmppCore;

/**
 * Primitive type to represent SMSes.
 *
 * @author OnlineCity <hd@onlinecity.dk>
 */
class SmppSms extends SmppPdu
{
    public $serviceType;
    public $source;
    public $destination;
    public $esmClass;
    public $protocolId;
    public $priorityFlag;
    public $registeredDelivery;
    public $dataCoding;
    public $message;
    public $tags;

    // Unused in deliver_sm
    public $scheduleDeliveryTime;
    public $validityPeriod;
    public $smDefaultMsgId;
    public $replaceIfPresentFlag;

    /**
     * Construct a new SMS.
     *
     * @param int         $id
     * @param int         $status
     * @param int         $sequence
     * @param string      $body
     * @param string      $serviceType
     * @param SmppAddress $source
     * @param SmppAddress $destination
     * @param int         $esmClass
     * @param int         $protocolId
     * @param int         $priorityFlag
     * @param int         $registeredDelivery
     * @param int         $dataCoding
     * @param string      $message
     * @param array       $tags
     * @param string      $scheduleDeliveryTime
     * @param string      $validityPeriod
     * @param int         $smDefaultMsgId
     * @param int         $replaceIfPresentFlag
     */
    public function __construct($id,
                                $status,
                                $sequence,
                                $body,
                                $serviceType,
                                SmppAddress $source,
                                SmppAddress $destination,
                                $esmClass,
                                $protocolId,
                                $priorityFlag,
                                $registeredDelivery,
                                $dataCoding,
                                $message,
                                $tags,
                                $scheduleDeliveryTime = null,
                                $validityPeriod = null,
                                $smDefaultMsgId = null,
                                $replaceIfPresentFlag = null)
    {
        parent::__construct($id, $status, $sequence, $body);
        $this->serviceType = $serviceType;
        $this->source = $source;
        $this->destination = $destination;
        $this->esmClass = $esmClass;
        $this->protocolId = $protocolId;
        $this->priorityFlag = $priorityFlag;
        $this->registeredDelivery = $registeredDelivery;
        $this->dataCoding = $dataCoding;
        $this->message = $message;
        $this->tags = $tags;
        $this->scheduleDeliveryTime = $scheduleDeliveryTime;
        $this->validityPeriod = $validityPeriod;
        $this->smDefaultMsgId = $smDefaultMsgId;
        $this->replaceIfPresentFlag = $replaceIfPresentFlag;
    }
}
