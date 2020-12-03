<?php

namespace Krak\Admin\Form;

final class Field
{
    private $name;
    private $type;
    private $value;

    public function __construct(string $name, string $type, $value) {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    public static function string(string $name, ?string $value): self {
        return new self($name, 'string', $value);
    }

    public static function int(string $name, ?int $value): self {
        return new self($name, 'int', $value);
    }

    public function isString(): bool {
        return $this->type === 'string';
    }

    public function isInt(): bool {
        return $this->type === 'int';
    }

    public function name(): string {
        return $this->name;
    }

    public function type(): string {
        return $this->type;
    }

    public function value() {
        return $this->value;
    }
}
