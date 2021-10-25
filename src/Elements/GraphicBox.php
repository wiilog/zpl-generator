<?php

namespace ZplGenerator\Elements;

use ZplGenerator\Context;

class GraphicBox extends Element {

    private float $height;

    private float $borderWidth = 1;

    private int $rounding = 0;

    public static function create(?float $x, ?float $y): self {
        return new self($x, $y);
    }

    public function setWidth(float $width): self {
        $this->width = $width;
        return $this;
    }

    public function setHeight(float $height): self {
        $this->height = $height;
        return $this;
    }

    public function setBorderWidth(float $borderWidth): self {
        $this->borderWidth = $borderWidth;
        return $this;
    }

    public function setRounding(int $rounding): self {
        $this->rounding = $rounding;
        return $this;
    }

    public function toZPL(Context $context) {
        $this->addPosition($context);

        $context->gb($this->width, $this->height, $this->borderWidth ?: 1, $this->borderWidth != 0 ? "B" : "W", $this->rounding);
    }

}
