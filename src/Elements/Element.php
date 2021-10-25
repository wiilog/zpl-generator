<?php


namespace ZplGenerator\Elements;

use ZplGenerator\Context;
use ZplGenerator\Elements\Common\Align;
use ZplGenerator\InvalidElementException;
use ZplGenerator\ToZPL;

abstract class Element implements ToZPL {

    protected ?Element $root = null;

    protected ?float $x;

    protected ?float $y;

    protected ?float $width = null;

    protected string $alignment = Align::LEFT;

    public function __construct(?float $x, ?float $y, ?float $width = null) {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
    }

    protected function getRoot(): ?Element {
        return $this->root;
    }

    protected function setRoot(?Element $root): self {
        $this->root = $root;
        return $this;
    }

    public function getAbsoluteX(int $dpi): ?float {
        //no parent means it's the root container
        if(!$this->root) {
            return 0;
        }

        if($this->alignment === Align::LEFT) {
            return$this->getX();
        } else if($this->alignment === Align::RIGHT) {
            return $this->root->getWidth($dpi) - $this->getX() - $this->getWidth($dpi);
        } else {
            return $this->root->getWidth($dpi) / 2 - $this->getX() - $this->getWidth($dpi) / 2;
        }
    }

    public function getAbsoluteY(int $dpi): ?float {
        //no parent means it's the root container
        if(!$this->root) {
            return 0;
        }
        return $this->getY();
    }

    public function getX(): ?float {
        return $this->x;
    }

    public function getY(): ?float {
        return $this->y;
    }

    public function setPosition(?float $x = null, ?float $y = null): self {
        $this->x = $x ?? $this->x;
        $this->y = $y ?? $this->y;
        return $this;
    }

    public function getWidth(int $dpi): ?float {
        return $this->width;
    }

    public function setWidth(float $width): self {
        $this->width = $width;
        return $this;
    }

    public function getAlignment(): string {
        return $this->alignment;
    }

    public function setAlignment(?string $alignment): self {
        $this->alignment = $alignment;
        return $this;
    }

    protected function addPosition(Context $context) {
        if($this->getX() === null || $this->getY() === null) {
            throw new InvalidElementException("ZPL element has no position");
        }

        $context->fo(
            $context->toDots(Context::X, $this->getAbsoluteX($context->getDPI())),
            $context->toDots(Context::Y, $this->getAbsoluteY($context->getDPI()))
        );
    }

}
