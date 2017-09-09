<?php

namespace Kronas\SmppClientBundle\Encoder;

interface EncoderInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public static function encode($string);
}
