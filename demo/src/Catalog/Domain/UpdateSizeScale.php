<?php

namespace Demo\App\Catalog\Domain;

final class UpdateSizeScale
{
    private $sizeScaleId;
    private $name;
    private $sizes;

    public function __construct(int $sizeScaleId, string $name, array $sizes) {
        $this->sizeScaleId = $sizeScaleId;
        $this->name = $name;
        $this->sizes = $sizes;
    }

    public function sizeScaleId(): int {
        return $this->sizeScaleId;
    }

    public function name(): string {
        return $this->name;
    }

    public function sizes(): array {
        return $this->sizes;
    }
}
