<?php

namespace ZplGenerator;

use RuntimeException;
use ZplGenerator\Elements\Common\Orientation;
use ZplGenerator\Elements\Container;
use ZplGenerator\Elements\Element;
use ZplGenerator\Elements\Text\TextConfig;
use ZplGenerator\Printer\Printer;

class Label {

    private Printer $printer;

    private Container $elements;

    public function __construct(Printer $printer) {
        $this->printer = $printer;
        $this->elements = Container::create(0, 0, $printer->getWidth());
    }

    public function with(Element $element): self {
        $this->elements->add($element);
        return $this;
    }

    public function toZPL(): string {
        $context = new Context($this->printer, TextConfig::default(), false);

        return $context
            ->xa()
            ->po(Orientation::R0)
            ->with($context->getMode())
            ->pw($context->toDots(Context::X, $context->getWidth()))
            ->ll($context->toDots(Context::Y, $context->getHeight()))
            ->ci(28)
            ->with($this->elements)
            ->xz()
            ->getOutput();
    }

    public function print() {
        if(!$this->printer) {
            throw new RuntimeException("Label is not linked to a printer");
        }

        $this->printer->print($this);
    }

}
