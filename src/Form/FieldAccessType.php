<?php

namespace Krak\Admin\Form;

use Krak\ADT\ADT;

abstract class FieldAccessType extends ADT
{
    public static function types(): array {
        return [ReadOnlyFieldAccessType::class, WriteOnlyFieldAccessType::class, ReadWriteFieldAccessType::class];
    }

    public static function readOnly() { return new ReadOnlyFieldAccessType(); }
    public static function writeOnly() { return new WriteOnlyFieldAccessType(); }
    public static function readWrite() { return new ReadWriteFieldAccessType(); }
}

final class ReadOnlyFieldAccessType extends FieldAccessType {}
final class WriteOnlyFieldAccessType extends FieldAccessType {}
final class ReadWriteFieldAccessType extends FieldAccessType {}
