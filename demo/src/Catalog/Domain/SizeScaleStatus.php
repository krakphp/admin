<?php

namespace Demo\App\Catalog\Domain;

abstract class SizeScaleStatus
{
    private $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public static function draft(): self { return new SizeScaleStatusDraft(); }
    public static function published(): self { return new SizeScaleStatusPublished(); }
    public static function archived(): self { return new SizeScaleStatusArchived(); }

    public function isDraft(): bool { return $this->value === 'draft'; }
    public function isPublished(): bool { return $this->value === 'published'; }
    public function isArchived(): bool { return $this->value === 'archived'; }

    public static function fromString(string $value): self {
        switch ($value) {
        case 'draft': return self::draft();
        case 'published': return self::published();
        case 'archived': return self::archived();
        default: throw new \InvalidArgumentException('Unexpected value for SizeScale: ' . $value);
        }
    }

    public function __toString() {
        return $this->value;
    }
}

final class SizeScaleStatusDraft extends SizeScaleStatus { public function __construct() { parent::__construct('draft'); } }
final class SizeScaleStatusPublished extends SizeScaleStatus { public function __construct() { parent::__construct('published'); } }
final class SizeScaleStatusArchived extends SizeScaleStatus { public function __construct() { parent::__construct('archived'); } }
