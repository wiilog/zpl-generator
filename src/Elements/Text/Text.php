<?php

namespace ZplGenerator\Elements\Text;

use ZplGenerator\Context;
use ZplGenerator\Elements\Element;

class Text extends Element {

    public const LINE_SEPARATOR = "\\&";

    private string $text;

    private ?TextConfig $config = null;

    private int $maxLines = 3;

    private float $spacing = 0;

    private float $indent = 0;

    public static function create(?float $x, ?float $y): self {
        return new self($x, $y);
    }

    //no matter the alignment return the left aligned
    //position since text alignment is handled by the field
    //block command
    public function getAbsoluteX(Context $context): ?float {
        return $this->getX();
    }

    public function setText(string $text): self {
        $this->text = $text;
        return $this;
    }

    public function setConfig(?TextConfig $config): self {
        $this->config = $config;
        return $this;
    }

    public function setMaxLines(int $maxLines): self {
        $this->maxLines = $maxLines;
        return $this;
    }

    public function setSpacing(int $spacing): self {
        $this->spacing = $spacing;
        return $this;
    }

    public function setIndent(float $indent): self {
        $this->indent = $indent;
        return $this;
    }

    public function toZPL(Context $context) {
        $this->addPosition($context);

        $width = $this->width ?? ($context->getWidth() - $this->getAbsoluteX($context));
        $width = $context->toDots(Context::X, $width, true);

        $context->with($this->config ?? $context->getDefaultTextConfig())
            ->fb($width, $this->maxLines, $this->spacing, $this->alignment, $this->indent)
            ->fh("_")
            ->fd($this->text . self::LINE_SEPARATOR)
            ->fs();
    }

}