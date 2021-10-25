<?php

namespace ZplGenerator;

use RuntimeException;
use ZplGenerator\Elements\Text\TextConfig;
use ZplGenerator\Printer\Printer;
use ZplGenerator\Printer\PrintMode;

class Context {

    public const X = 1;
    public const Y = 2;

    private Printer $printer;

    private TextConfig $defaultTextConfig;

    private bool $nl;

    private array $zpl = [];

    public function __construct(Printer $printer, TextConfig $defaultTextConfig, bool $nl = false) {
        $this->printer = $printer;
        $this->defaultTextConfig = $defaultTextConfig;
        $this->nl = $nl;
    }

    public function getWidth(): float {
        return $this->printer->getWidth();
    }

    public function getHeight(): float {
        return $this->printer->getHeight();
    }

    public function getMode(): ?PrintMode {
        return $this->printer->getMode();
    }

    public function getDPI(): int {
        return $this->printer->getDPI();
    }

    public function getDefaultTextConfig(): ?TextConfig {
        return $this->defaultTextConfig;
    }

    public function toDots(int $direction, float $value, bool $round = false) {
        //unit in mm
        //return round($value * 1 / 25.4 * $this->getDPI(), $round ? 0 : 4);

        //unit in %
        if($direction === self::X) {
            return round($this->getWidth() * $value / 100 * 1 / 25.4 * $this->getDPI(), $round ? 0 : 4);
        } else {
            return round($this->getHeight() * $value / 100 * 1 / 25.4 * $this->getDPI(), $round ? 0 : 4);
        }
    }

    public function __call(string $method, array $arguments) {
        return $this->command($method, ...$arguments);
    }

    public function command(string $command, ...$params): self {
        if(strlen($command) < 1 || strlen($command) > 2) {
            throw new RuntimeException("Invalid ZPL command \"$command\"");
        }

        $this->zpl[] = "^" . strtoupper($command) . join(",", array_map([$this, "parameter"], $params));
        return $this;
    }

    public function with($item): self {
        if($item instanceof ToZPL) {
            $item->toZPL($this);
        } else if(is_array($item)) {
            $this->zpl = array_merge($this->zpl, $item);
        } else {
            $this->zpl[] = $item;
        }

        return $this;
    }

    protected function parameter($parameter) {
        if(is_bool($parameter)) {
            return $parameter ? "Y" : "N";
        }

        return $parameter;
    }

    public function getOutput(): string {
        return join($this->nl ? "\n" : "", $this->zpl);
    }

}