<?php

namespace ZplGenerator\Test;

use ZplGenerator\Context;
use ZplGenerator\Elements\Text\TextConfig;
use ZplGenerator\Label;
use ZplGenerator\Printer\Printer;
use ZplGenerator\ToZPL;

class TestUtils {

    public static function createPrinter(): Printer {
        return Printer::create("127.0.0.1", 9100, true)
            ->setDPI(Printer::DPI_203)
            ->setDimension(102.6, 102.6);
    }

    public static function createLabel(): Label {
        return self::createPrinter()->createLabel();
    }

    public static function createContext(): Context {
        return new Context(self::createPrinter(), TextConfig::default(), false);
    }

    public static function toZPL(ToZPL $item): string {
        $context = self::createContext();
        $item->toZPL($context);

        return $context->getOutput();
    }

}