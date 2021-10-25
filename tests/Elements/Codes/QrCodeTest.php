<?php

namespace ZplGenerator\Test\Elements\Codes;

use PHPUnit\Framework\TestCase;
use ZplGenerator\Elements\Common\Align;
use ZplGenerator\Elements\Codes\QrCode;
use ZplGenerator\Test\TestUtils;

class QrCodeTest extends TestCase {

    public function testToZPL() {
        $expected = "^FO0,8.1999^BQN,2,7,H^FDMM,A,A-2021092000001^FS^FO0,192.6981^A0N,32.7997,32.7997^FB841.3119,1,0,C,0^FH_^FDA-2021092000001\&^FS";
        $text = QrCode::create(1, 1)
            ->setContent("A-2021092000001")
            ->setDisplayContent(true)
            ->setAlignment(Align::CENTER)
            ->setSize(7)
            ->setErrorCorrection(QrCode::EC_HIGHEST);

        $this->assertEquals($expected, TestUtils::toZPL($text));
    }

}