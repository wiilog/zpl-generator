<?php

namespace ZplGenerator\Elements;

use ZplGenerator\Context;

class Container extends Element {

    private array $elements = [];

    public static function create(?float $x, ?float $y, ?float $width): self {
        return new self($x, $y, $width);
    }

    public function add(Element $element): self {
        $this->elements[] = $element;
        $element->setRoot($this);

        return $this;
    }

    public function toZPL(Context $context) {
        foreach($this->elements as $element) {
            $context->with($element);
        }
    }

}