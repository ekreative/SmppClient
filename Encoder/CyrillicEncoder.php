<?php

namespace Kronas\SmppClientBundle\Encoder;

class CyrillicEncoder implements EncoderInterface
{
    /**
     * Encode an UTF-8 string into UCS-2BE.
     *
     * @param string $string
     *
     * @return string
     */
    public static function encode($string)
    {
        return mb_convert_encoding($string, 'UCS-2BE', 'UTF-8');
    }
}
