<?php

namespace ZplGenerator\Test;

use PHPUnit\Framework\TestCase;
use ZplGenerator\Elements\Codes\QrCode;
use ZplGenerator\Elements\Common\Align;
use ZplGenerator\Elements\Text\Text;
use ZplGenerator\Printer\Printer;

class LabelTest extends TestCase {

    public function testBasicLabel() {
        $expected = "^XA^PON^MMT,N^PW819.9921^LL819.9921^FO310.0945,119.8819^BQN,2,10,H^FDMM,A,https://wiilog.fr/^FS^FO0,39.9606^A0N,55.9449,55.9449^FB820,1,0,C,0^FH_^FDWiilog\&^FS^XZ";

        $logo = Text::create(0, 5)
            ->setWidth(102.6)
            ->setText("Wiilog")
            ->setAlignment(Align::CENTER)
            ->setMaxLines(1);

        $qr = QrCode::create(0, 15)
            ->setContent("https://wiilog.fr/")
            ->setSize(10)
            ->setAlignment(Align::CENTER)
            ->setErrorCorrection(QrCode::EC_HIGHEST);

        $printer = Printer::create("127.0.0.1", 9100, true)
            ->setDimension(102.6, 102.6)
            ->setDPI(Printer::DPI_203);

        $label = $printer->createLabel()
            ->with($qr)
            ->with($logo);

        $this->assertEquals($expected, $label->toZPL());
    }

}
