<?php

namespace Demo\App\Catalog\Domain;

final class UpdateSizeScale
{
    private $sizeScaleId;
    private $name;

    public function __construct(int $sizeScaleId, string $name) {
        $this->sizeScaleId = $sizeScaleId;
        $this->name = $name;
    }

    public function sizeScaleId(): int {
        return $this->sizeScaleId;
    }

    public function name(): string {
        return $this->name;
    }
}
