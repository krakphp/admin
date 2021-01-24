<?php

namespace League\Plates\Bridge\AlpineJs;

use function League\Plates\attrs;
use function League\Plates\escape;
use function League\Plates\p;

abstract class AlpineJs
{
    public static function Component($data, $children, array $attributes = []) {
        return p(function() use ($data, $children, $attributes) {
            ?>  <div <?=attrs(['x-data' => self::data($data)], $attributes)?>>
                <?=p($children)?>
            </div> <?php
        });
    }

    public static function data(array $data): string {
        return escape(json_encode($data));
    }
}
