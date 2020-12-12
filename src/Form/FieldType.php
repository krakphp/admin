<?php

namespace Krak\Admin\Form;

abstract class FieldType
{
    private $type;

    public function __construct(string $type) {
        $this->type = $type;
    }

    public static function int(): IntFieldType { return new IntFieldType(); }
    public static function float(): FloatFieldType { return new FloatFieldType(); }
    public static function color(): ColorFieldType { return new ColorFieldType(); }
    public static function string(): StringFieldType { return new StringFieldType(); }
    public static function dateTime(): DateTimeFieldType { return new DateTimeFieldType(); }
    public static function enum(array $acceptedValues): EnumFieldType { return new EnumFieldType($acceptedValues); }

    public function __toString() {
        return $this->type;
    }
}

final class IntFieldType extends FieldType {
    public function __construct() {
        parent::__construct('int');
    }
}

final class FloatFieldType extends FieldType {
    public function __construct() {
        parent::__construct('int');
    }
}

final class ColorFieldType extends FieldType {
    public function __construct() {
        parent::__construct('color');
    }
}

final class StringFieldType extends FieldType {
    public function __construct() {
        parent::__construct('string');
    }
}

final class DateTimeFieldType extends FieldType {
    public function __construct() {
        parent::__construct('datetime');
    }
}

final class EnumFieldType extends FieldType {
    private $acceptedValues;

    public function __construct(array $acceptedValues) {
        parent::__construct('enum');
        $this->acceptedValues = $acceptedValues;
    }

    public function acceptedValues(): array {
        return $this->acceptedValues;
    }
}
