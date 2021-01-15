<?php

namespace League\Plates;

function e(string $string, $flags = ENT_COMPAT | ENT_HTML401, string $encoding = 'UTF-8', bool $doubleEncode = true): string {
    return htmlspecialchars($string, $flags, $encoding, $doubleEncode);
}

/**
 * Converts array like `['required' => null, 'class' => 'abc''] to a stringable like: 'required class="abc"' to be inserted
 * into html strings.
 */
function attrs(...$attributeSets): HTMLAttributeSet {
    return new HTMLAttributeSet($attributeSets);
}

function classNames(...$values): ClassNameAttrSet {
    return ClassNameAttrSet::fromValues(...$values);
}

final class ClassNameAttrSet {
    const MODE_ADD = 'add';
    const MODE_REPLACE = 'replace';

    private $classNames;
    private $mode;

    /** @param ClassNameAttr[] $classNames */
    private function __construct(array $classNames) {
        $this->classNames = $classNames;
        $this->mode = self::MODE_ADD;
    }

    public static function fromValues(...$values): self {
        return new self(array_map(function($value) {
            return new ClassNameAttr($value);
        }, array_filter($values)));
    }

    public static function wrap($value): self {
        return $value instanceof self ? $value : (self::fromValues($value));
    }

    public function replace(): self {
        $self = clone $this;
        $self->mode = self::MODE_REPLACE;
        return $self;
    }

    public function add(): self {
        $self = clone $this;
        $self->mode = self::MODE_ADD;
        return $self;
    }

    public function merge(ClassNameAttrSet $set): self {
        if ($set->mode === self::MODE_REPLACE) {
            return $set;
        }

        $self = clone $this;
        $self->classNames = array_merge($this->classNames, $set->classNames);
        return $self;
    }

    public function mode(): string {
        return $this->mode;
    }

    public function __toString(): string {
        $containsMapTypeClassName = $this->containsMapClassName();
        if (!$containsMapTypeClassName) {
            return implode(' ', array_map(function(ClassNameAttr $cn) {
                return $cn->toString();
            }, $this->classNames));
        }

        return implode(// join keys by space
            ' ',
            array_keys(// take all truthy keys left since these are the class names
                array_filter(// remove all falsey values
                    array_merge(// merge all of the maps together
                        ...array_map(function(ClassNameAttr $cn) {
                            return $cn->toMap();
                        }, $this->classNames)
                    )
                )
            )
        );
    }

    private function containsMapClassName(): bool {
        foreach ($this->classNames as $className) {
            if ($className->type() === ClassNameAttr::TYPE_MAP) {
                return true;
            }
        }

        return false;
    }
}

final class ClassNameAttr {
    const MODE_ADD = 'add';
    const MODE_REPLACE = 'replace';

    const TYPE_STRING = 'string';
    const TYPE_LIST = 'list';
    const TYPE_MAP = 'map';

    private $value;
    private $type;

    public function __construct($value) {
        $this->value = $value;
        $this->type = $this->determineType($value);
        $this->mode = self::MODE_ADD;
    }

    private function determineType($value): string {
        if (is_string($this->value)) {
            return self::TYPE_STRING;
        }
        if (is_array($this->value) && is_int(key($this->value))) {
            return self::TYPE_LIST;
        }
        if (is_array($this->value) && is_string(key($this->value))) {
            return self::TYPE_MAP;
        }
        throw new \RuntimeException('Expected a class name value to be a string, list of class name strings, or map of class names to true/falsy values');
    }

    public function value() {
        return $this->value;
    }

    public function type(): string {
        return $this->type;
    }

    public function toString(): string {
        if ($this->type === self::TYPE_STRING) {
            return $this->value;
        }
        if ($this->type === self::TYPE_LIST) {
            return implode(' ', array_filter($this->value));
        }

        throw new \RuntimeException('Cannot convert map className to a string.');
    }

    public function toMap(): array {
        if ($this->type === self::TYPE_MAP) {
            return $this->value;
        }
        if ($this->type === self::TYPE_LIST) {
            $map = [];
            foreach ($this->value as $className) {
                $map[$className] = true;
            }
            return $map;
        }
        if ($this->type === self::TYPE_STRING) {
            $map = [];
            foreach (explode(' ', $this->value) as $className) {
                if ($className) { // if we have extra spaces in a string like "mb-1  mb-2" explode will output empty string entries
                    $map[$className] = true;
                }
            }
            return $map;
        }
    }
}

final class HTMLAttributeSet implements \IteratorAggregate
{
    private $attributeSets;

    public function __construct(array $attributeSets) {
        $this->attributeSets = $attributeSets;
    }

    public function getIterator() {
        foreach ($this->attributeSets as $attributeSet) {
            foreach ($attributeSet as $key => $value) {
                yield $key => $value;
            }
        }
    }

    public function __toString(): string {
        $attrMap = [];
        foreach ($this as $key => $value) {
            if ($key === 'class') { // use custom class name attribute set merging semantics
                $attrMap[$key] = ClassNameAttrSet::wrap($attrMap[$key] ?? null)->merge(ClassNameAttrSet::wrap($value));
            } else { // replace values
                $attrMap[$key] = $value;
            }
        }
        $html = '';
        foreach ($attrMap as $key => $value) {
            if ($value !== null) {
                $html .= " {$key}=\"$value\"";
            } else {
                $html .= " {$key}";
            }
        }
        return ltrim($html);
    }
}
