<?php

namespace League\Plates\Bridge\AlpineJs;

use function League\Plates\escape;
use function League\Plates\h;

abstract class AlpineJs
{
    public static function Component($data, $children, ...$attributes) {
        return h('div', $children, ['x-data' => self::data($data)], ...$attributes);
    }

    public static function data(array $data): string {
        return escape(json_encode($data));
    }
}
