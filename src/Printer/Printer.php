<?php

namespace ZplGenerator\Printer;

use RuntimeException;
use ZplGenerator\Client\Client;
use ZplGenerator\Client\SocketClient;
use ZplGenerator\Label;

class Printer {

    public const DPI_152 = 152;
    public const DPI_203 = 203;
    public const DPI_300 = 300;
    public const DPI_600 = 600;

    private float $width;

    private float $height;

    private int $dpi;

    private int $timeout = 30;

    private PrintMode $mode;

    public function __construct() {
        $this->mode = new PrintMode(PrintMode::TEAR_OFF);
    }

    public static function create(): self {
        return new static();
    }

    public function getWidth(): float {
        return $this->width;
    }

    public function getHeight(): float {
        return $this->height;
    }

    public function setDimension(float $width, float $height): self {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function getDPI(): int {
        return $this->dpi;
    }

    public function setDPI(int $dpi): self {
        $this->dpi = $dpi;
        return $this;
    }

    public function setTimeout(int $timeout): Printer {
        $this->timeout = $timeout;
        return $this;
    }

    public function getMode(): PrintMode {
        return $this->mode;
    }

    public function setMode(PrintMode $mode): self {
        $this->mode = $mode;
        return $this;
    }

    public function createLabel(): Label {
        return new Label($this);
    }

    public function print(Client $client, Label|string ...$items): void {
        $zpl = "";
        foreach($items as $item) {
            if($item instanceof Label) {
                $zpl .= $item->toZPL();
            } else if(is_string($item)) {
                $zpl .= $item;
            } else {
                throw new RuntimeException("Received unprintable object");
            }
        }

        $client->send($zpl);
    }

}