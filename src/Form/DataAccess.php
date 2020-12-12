<?php

namespace Krak\Admin\Form;

final class DataAccess
{
    public static function alwaysEmpty(): callable {
        return function(string $field) {
            return null;
        };
    }
}
