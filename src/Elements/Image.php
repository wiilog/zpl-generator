<?php

namespace ZplGenerator\Elements;

use Zebra\Zpl\Image as ZplImage;
use ZplGenerator\Context;
use ZplGenerator\Utils\GdDecoder;

class Image extends Element {

    private GdDecoder $image;

    private ?float $height = null;

    public static function fromPath(?float $x, ?float $y, string $path): self {
        $image = new Image($x, $y);
        $image->image = GdDecoder::fromPath($path);

        return $image;
    }

    public static function fromString(?float $x, ?float $y, string $content): self {
        $image = new Image($x, $y);
        $image->image = GdDecoder::fromString($content);

        return $image;
    }

    public function setHeight(?float $height): Image {
        $this->height = $height;
        return $this;
    }

    public function toZPL(Context $context) {
        $this->image->resize($context, $this->width, $this->height, true);
        $image = new ZplImage($this->image);

        $bytesPerRow = $image->width();
        $bytesCount = $bytesPerRow * $image->height();

        $this->addPosition($context);
        $context->gf("A", $bytesCount, $bytesCount, $bytesPerRow, $image->toAscii())->fs();
    }

}
