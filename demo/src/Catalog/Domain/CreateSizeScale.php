<?php

namespace Demo\App\Catalog\Domain;

final class CreateSizeScale
{
    private $name;
    private $sizes;

    public function __construct(string $name, array $sizes) {
        $this->name = $name;
        $this->sizes = $sizes;
    }

    public function name(): string {
        return $this->name;
    }

    public function sizes(): array {
        return $this->sizes;
    }
}
