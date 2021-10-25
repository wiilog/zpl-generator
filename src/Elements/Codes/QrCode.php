<?php

namespace ZplGenerator\Elements\Codes;

use RuntimeException;
use ZplGenerator\Context;
use ZplGenerator\Elements\Element;
use ZplGenerator\Elements\Text\Text;
use ZplGenerator\Elements\Text\TextConfig;

class QrCode extends Element {

    public const EC_HIGHEST = "H";
    public const EC_HIGH = "Q";
    public const EC_NORMAL = "M";
    public const EC_NONE = "L";

    private string $content;

    private string $errorCorrection = self::EC_HIGH;

    private ?int $size = null;

    private bool $displayContent = false;

    private ?TextConfig $textConfig = null;

    public function __construct(?float $x, ?float $y, ?float $width = null) {
        parent::__construct($x, $y, $width);
        $this->textConfig = new TextConfig(null, 4, null);
    }

    public static function create(?float $x, ?float $y): self {
        return new self($x, $y);
    }

    public function setContent(string $content): QrCode {
        $this->content = $content;
        return $this;
    }

    public function setErrorCorrection(string $errorCorrection): QrCode {
        $this->errorCorrection = $errorCorrection;
        return $this;
    }

    public function setSize(?int $size): QrCode {
        if($size < 0 || $size > 10) {
            throw new RuntimeException("QR code size must be between 1 and 10");
        }

        $this->size = $size;
        return $this;
    }

    public function setDisplayContent(bool $displayContent): self {
        $this->displayContent = $displayContent;
        return $this;
    }

    public function setTextConfig(?TextConfig $textConfig): self {
        $this->textConfig = $textConfig;
        return $this;
    }

    public function getWidth(Context $context): ?float {
        return 2.5 * 203 / $context->getDPI() * $this->size;
    }

    public function setWidth(float $width): Element {
        throw new RuntimeException("Can not set QR code width, use setSize instead");
    }

    public function toZPL(Context $context) {
        $this->addPosition($context);

        $context->bq("N", 2, $this->size, $this->errorCorrection)
            ->fd("MM", "A$this->content")
            ->fs();

        if($this->displayContent)  {
            //height is same as width since a QR code is a square
            $height = $this->getWidth($context);

            $context->fo(0, $context->toDots(Context::Y, $this->getAbsoluteY($context) + $height + 5))
                ->with($this->textConfig ?? $context->getDefaultTextConfig())
                ->fb($context->toDots(Context::X, $context->getWidth()), 1, 0, $this->alignment, 0)
                ->fh("_")
                ->fd($this->content . Text::LINE_SEPARATOR)
                ->fs();
        }
    }

}
