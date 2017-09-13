<?php

namespace Kronas\SmppClientBundle\Encoder;

use PHPUnit\Framework\TestCase;

final class CyrillicEncoderTest extends TestCase
{
    /**
     * @dataProvider getSampleText
     * @param $string
     */
    public function testEncode($string)
    {
        $newString = CyrillicEncoder::encode($string);
        $this->assertNotEquals($string, $newString);

        $decodedString = mb_convert_encoding($newString, 'UTF-8', 'UCS-2BE');
        $this->assertEquals($string, $decodedString);
    }

    public function getSampleText() {
        return [
            ['This is the test message'],
            ['Это тестовое сообщение']
        ];
    }
}
