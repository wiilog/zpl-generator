<?php

namespace ZplGenerator\Elements;

use Zebra\Zpl\GdDecoder;
use Zebra\Zpl\Image as ZplImage;
use ZplGenerator\Context;

class Image extends Element {

    private ZplImage $image;

    public static function fromPath(?float $x, ?float $y, string $path): self {
        $image = new Image($x, $y);
        $image->image = new ZplImage(GdDecoder::fromPath($path));

        return $image;
    }

    public static function fromString(?float $x, ?float $y, string $content): self {
        $image = new Image($x, $y);
        $image->image = new ZplImage(GdDecoder::fromString($content));

        return $image;
    }

    public function toZPL(Context $context) {
        $bytesPerRow = $this->image->width();
        $bytesCount = $bytesPerRow * $this->image->height();

        $this->addPosition($context);
        $context->gf("A", $bytesCount, $bytesCount, $bytesPerRow, $this->image->toAscii())->fs();
    }

}
