<?php

namespace ZplGenerator\Test\Elements\Text;

use PHPUnit\Framework\TestCase;
use ZplGenerator\Elements\Common\Align;
use ZplGenerator\Elements\Text\Text;
use ZplGenerator\Test\TestUtils;

class TextTest extends TestCase {

    public function testToZPL() {
        $expected = "^FO409.9961,204.998^A0N,57.3994,57.3994^FB410,1,5,R,0^FH_^FDtexte simple\&^FS";
        $text = Text::create(50, 25)
            ->setText("texte simple")
            ->setAlignment(Align::RIGHT)
            ->setSpacing(5)
            ->setMaxLines(1)
            ->setWidth(50);

        $this->assertEquals($expected, TestUtils::toZPL($text));
    }

    public function testToZPLSpecialCharacters() {
        $expected = "^FO409.9961,204.998^A0N,57.3994,57.3994^FB164,7,5,C,0^FH_^FDtéxte @vec des char@ctères \$péciauX\&^FS";
        $text = Text::create(50, 25)
            ->setText("téxte @vec des char@ctères \$péciauX")
            ->setAlignment(Align::CENTER)
            ->setSpacing(5)
            ->setMaxLines(7)
            ->setWidth(20);

        $this->assertEquals($expected, TestUtils::toZPL($text));
    }

    public function testToZPLEmpty() {
        $expected = "^FO409.9961,204.998^A0N,57.3994,57.3994^FB328,7,5,L,0^FH_^FD\&^FS";
        $text = Text::create(50, 25)
            ->setText("")
            ->setAlignment(Align::LEFT)
            ->setSpacing(5)
            ->setMaxLines(7)
            ->setWidth(40);

        $this->assertEquals($expected, TestUtils::toZPL($text));
    }

}