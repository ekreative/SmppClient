<?php

namespace Kronas\SmppClientBundle\Encoder;

/**
 * Class capable of encoding GSM 03.38 default alphabet and packing octets into septets as described by GSM 03.38.
 * Based on mapping: http://www.unicode.org/Public/MAPPINGS/ETSI/GSM0338.TXT.
 *
 * Copyright (C) 2011 OnlineCity
 * Licensed under the MIT license, which can be read at: http://www.opensource.org/licenses/mit-license.php
 *
 * @author OnlineCity <hd@onlinecity.dk>
 */
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
