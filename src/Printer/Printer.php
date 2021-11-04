<?php

namespace ZplGenerator\Printer;

use RuntimeException;
use ZplGenerator\Label;

class Printer {

    public const DPI_152 = 152;
    public const DPI_203 = 203;
    public const DPI_300 = 300;
    public const DPI_600 = 600;

    private ?Client $client = null;

    private string $address;

    private int $port;

    private float $width;

    private float $height;

    private int $dpi;

    private int $timeout = 30;

    private PrintMode $mode;

    public function __construct(string $address, int $port = 9100, int $timeout = 10, bool $lazy = false) {
        $this->address = $address;
        $this->port = $port;
        $this->mode = new PrintMode(PrintMode::TEAR_OFF);
        if(!$lazy) {
            $this->client = new Client($address, $port, $timeout);
        }
    }

    public static function create(string $host, int $port = 9100, int $timeout = 10, bool $lazy = false): self {
        return new static($host, $port, $timeout, $lazy);
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

    public function connect() {
        if(!$this->client) {
            $this->client = new Client($this->address, $this->port, $this->timeout);
        }
    }

    public function print(...$items) {
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

        $this->connect();
        $this->client->send($zpl);
    }

}