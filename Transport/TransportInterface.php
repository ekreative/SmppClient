<?php

namespace Kronas\SmppClientBundle\Transport;

/**
 * Interface TransportInterface.
 */
interface TransportInterface
{
    public function open();

    public function close();

    /**
     * @param int $length
     *
     * @return null|string
     */
    public function read($length);

    /**
     * @param int $length
     *
     * @return null|string
     */
    public function readAll($length);

    /**
     * @param mixed $buffer
     * @param int   $length
     */
    public function write($buffer, $length);
}
