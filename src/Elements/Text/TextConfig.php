<?php

namespace ZplGenerator\Elements\Text;

use RuntimeException;
use ZplGenerator\Context;
use ZplGenerator\Elements\Common\Orientation;
use ZplGenerator\ToZPL;

class TextConfig implements ToZPL {

    private string $font;

    private int $size;

    private string $rotation;

    public function __construct(string $font = null, int $size = null, string $rotation = null) {
        $this->font = $font ?? Font::ZEBRA_DEFAULT;
        $this->size = $size ?? 7;
        $this->rotation = $rotation ?? Orientation::R0;
    }

    public static function default() {
        return new TextConfig(Font::ZEBRA_DEFAULT, 7, Orientation::R0);
    }

    public function getFont(): string {
        return $this->font;
    }

    public function getSize(): int {
        return $this->size;
    }

    public function getRotation(): string {
        return $this->rotation;
    }

    public function toZPL(Context $context) {
        $context->a($this->font . $this->rotation, ...$this->dimensions($context));
    }

    private function dimensions(Context $context): array {
        if($this->font === Font::ZEBRA_DEFAULT) {
            [$x, $y] = [$this->size, $this->size];
        } else if($this->font === Font::ZEBRA_A) {
            [$x, $y] = [$this->size, $this->size * 0.5];
        } else if($this->font === Font::ZEBRA_B) {
            [$x, $y] = [$this->size, $this->size * 0.7];
        } else if($this->font === Font::ZEBRA_C) {
            [$x, $y] = [$this->size, $this->size * 0.5];
        } else if($this->font === Font::ZEBRA_D) {
            [$x, $y] = [$this->size, $this->size * 0.5];
        } else if($this->font === Font::ZEBRA_F) {
            [$x, $y] = [$this->size, $this->size * 0.5];
        } else {
            throw new RuntimeException("Unknown font \"$this->font\"");
        }

        return [
            $context->toDots(Context::X, $x),
            $context->toDots(Context::Y, $y),
        ];
    }

}