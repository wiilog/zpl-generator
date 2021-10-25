<?php

namespace ZplGenerator\Elements\Codes;

use ZplGenerator\Context;
use ZplGenerator\Elements\Common\Orientation;
use ZplGenerator\Elements\Element;
use ZplGenerator\Elements\Text\TextConfig;

class BarCode128 extends Element {

    public const TEXT_ABOVE = true;
    public const TEXT_BELOW = false;

    private string $orientation = Orientation::R0;

    private float $height;

    private string $content;

    private bool $displayText = true;

    private bool $textPosition = self::TEXT_BELOW;

    private ?TextConfig $textConfig = null;

    public static function create(?float $x, ?float $y): self {
        return new self($x, $y);
    }

    public function setOrientation(string $orientation): self {
        $this->orientation = $orientation;
        return $this;
    }

    public function setHeight(float $height): self {
        $this->height = $height;
        return $this;
    }

    public function setContent(string $content): self {
        $this->content = $content;
        return $this;
    }

    public function setDisplayText(bool $displayText): self {
        $this->displayText = $displayText;
        return $this;
    }

    public function setTextPosition(bool $textPosition): self {
        $this->textPosition = $textPosition;
        return $this;
    }

    public function setTextConfig(?TextConfig $textConfig): self {
        $this->textConfig = $textConfig;
        return $this;
    }

    public function toZPL(Context $context) {
        $this->addPosition($context);

        $context->with($this->textConfig ?? $context->getDefaultTextConfig())
            ->bc($this->orientation, $this->height, $this->displayText, $this->displayText && $this->textPosition)
            ->fd($this->content)
            ->fs();
    }

}
