<?php

namespace ZplGenerator\Test;

use PHPUnit\Framework\TestCase;
use ZplGenerator\Elements\Codes\QrCode;
use ZplGenerator\Elements\Common\Align;
use ZplGenerator\Elements\Text\Text;
use ZplGenerator\Printer\Printer;

class LabelTest extends TestCase {

    public function testBasicLabel() {
        $expected = "^XA^PON^MMT,N^PW841.3119^LL841.3119^CI28^FO307.497,122.9988^BQN,2,10,H^FDMM,Ahttps://wiilog.fr/^FS^FO0,40.9996^A0N,57.3994,57.3994^FB841,1,0,C,0^FH_^FDWiilog\&^FS^XZ";

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

        $printer = Printer::create()
            ->setDimension(102.6, 102.6)
            ->setDPI(Printer::DPI_203);

        $label = $printer->createLabel()
            ->with($qr)
            ->with($logo);

        $this->assertEquals($expected, $label->toZPL());
    }

}
