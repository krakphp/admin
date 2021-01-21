<?php

namespace Demo\App\Catalog\Domain;

class SizeScaleSize
{
    private $id;
    private $sizeScale;
    private $size;

    public function __construct(SizeScale $sizeScale, string $size) {
        $this->sizeScale = $sizeScale;
        $this->size = $size;
    }

    public function id(): ?int {
        return $this->id;
    }

    public function size(): string {
        return $this->size;
    }
}
