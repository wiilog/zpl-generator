<?php

namespace ZplGenerator\Utils;


use InvalidArgumentException;
use Zebra\Contracts\Zpl\Decoder;
use ZplGenerator\Context;

class GdDecoder implements Decoder {

    /** @var resource */
    protected $original;

    /** @var resource */
    protected $output;

    public static function fromResource($image): self {
        return new static($image);
    }

    public static function fromPath(string $path): self {
        return static::fromString(file_get_contents($path));
    }

    public static function fromString(string $data): self {
        if(false === $image = imagecreatefromstring($data)) {
            throw new InvalidArgumentException("Could not read image");
        }

        return new static($image);
    }

    public function __construct($image) {
        if(!$this->isGdResource($image)) {
            throw new InvalidArgumentException("Invalid resource");
        }

        if(!imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        imagefilter($image, IMG_FILTER_GRAYSCALE);

        $this->original = $image;
        $this->output = $image;
    }

    public function __destruct() {
        imagedestroy($this->original);
        imagedestroy($this->output);
    }

    public function isGdResource($image): bool {
        if(is_resource($image)) {
            return get_resource_type($image) === "gd";
        }

        return false;
    }

    public function width(): int {
        return imagesx($this->output);
    }

    public function height(): int {
        return imagesy($this->output);
    }

    public function resize(Context $context, ?int $width = null, ?int $height = null, bool $preserveRatio = true) {
        $currentWidth = imagesx($this->original);
        $currentHeight = imagesy($this->original);
        $width = $width ?? $currentWidth;
        $height = $height ?? $currentHeight;

        if($width === $currentWidth && $height === $currentHeight) {
            return;
        }

        if($preserveRatio) {
            $width = min($width, $height * $currentWidth / $currentHeight);
            $height = min($height, $width * $currentHeight / $currentWidth);
        }

        $this->output = imagescale($this->original, $context->toDots(Context::X, $width), $context->toDots(Context::Y, $height));
    }

    public function getBitAt(int $x, int $y): int {
        return (imagecolorat($this->output, $x, $y) & 0xFF) < 127 ? 1 : 0;
    }

}
