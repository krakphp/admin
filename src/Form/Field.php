<?php

namespace Krak\Admin\Form;

final class Field
{
    private $name;
    private $type;
    private $accessType;
    private $displayName;
    private $isRequired = false;

    public function __construct(string $name, FieldType $type, ?FieldAccessType $accessType = null, ?string $displayName = null) {
        $this->name = $name;
        $this->type = $type;
        $this->accessType = $accessType ?: FieldAccessType::readWrite();
        $this->displayName = $displayName;
    }

    public static function new(string $name, FieldType $type, ?FieldAccessType $accessType = null, ?string $displayName = null): self {
        return new self($name, $type, $accessType, $displayName);
    }

    public function name(): string {
        return $this->name;
    }

    public function type(): FieldType {
        return $this->type;
    }

    public function accessType(): FieldAccessType {
        return $this->accessType;
    }

    public function readOnly(): self {
        return $this->withAccessType(FieldAccessType::readOnly());
    }

    public function writeOnly(): self {
        return $this->withAccessType(FieldAccessType::writeOnly());
    }

    public function isRequired(): bool {
        return $this->isRequired;
    }

    public function required(): self {
        $self = clone $this;
        $self->isRequired = true;
        return $self;
    }

    public function optional(): self {
        $self = clone $this;
        $self->isRequired = false;
        return $self;
    }

    public function withAccessType(FieldAccessType $accessType): self {
        $self = clone $this;
        $self->accessType = $accessType;
        return $self;
    }

    public function displayName(): ?string {
        return $this->displayName;
    }

    /** returns the displayName prop if set, or just the fieldName */
    public function nameForDisplay(): string {
        return $this->displayName ?: $this->name;
    }

    public function withDisplayName(?string $displayName): self {
        $self = clone $this;
        $self->displayName = $displayName;
        return $self;
    }
}
