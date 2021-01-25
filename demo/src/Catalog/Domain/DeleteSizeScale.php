<?php

namespace Demo\App\Catalog\Domain;

final class DeleteSizeScale
{
    private $sizeScaleId;

    public function __construct(int $sizeScaleId) {
        $this->sizeScaleId = $sizeScaleId;
    }

    public function sizeScaleId(): int {
        return $this->sizeScaleId;
    }
}
