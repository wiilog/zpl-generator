<?php

namespace ZplGenerator\Printer;

use ZplGenerator\Context;
use ZplGenerator\ToZPL;

class PrintMode implements ToZPL {

    public const TEAR_OFF = "T";
    public const PEEL_OFF = "P";
    public const REWIND = "R";
    public const APPLICATOR = "A";
    public const CUTTER = "C";
    public const DELAYED_CUTTER = "D";
    public const RFID = "F";

    private string $mode;

    private bool $prepeel;

    public function __construct(string $mode, ?bool $prepeel = false) {
        $this->mode = $mode;
        $this->prepeel = $prepeel;
    }

    public function toZPL(Context $context) {
        $context->mm($this->mode, $this->prepeel);
    }

}
