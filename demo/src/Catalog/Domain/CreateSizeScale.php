<?php

namespace Demo\App\Catalog\Domain;

final class CreateSizeScale
{
    private $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function name(): string {
        return $this->name;
    }
}
