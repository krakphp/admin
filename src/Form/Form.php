<?php

namespace Krak\Admin\Form;

/**
 * Represents the structure of some entity/DTO. This is the in-between representation between the
 * view/component layer and the actual entities/DTOs designed to use be managed by the admin.
 */
final class Form
{
    private $name;
    private $fields;

    /** @param Field[] $fields */
    public function __construct(string $name, array $fields) {
        $this->name = $name;
        $this->fields = $fields;
    }

    public function name(): string {
        return $this->name;
    }

    public function fields(): array {
        return $this->fields;
    }

    public function withMappedFields(callable $map): self {
        $self = clone $this;
        $self->fields = array_map($map, $this->fields);
        return $self;
    }
}
