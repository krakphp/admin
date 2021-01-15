<?php

namespace League\Plates\Bridge\AlpineJs;

use function League\Plates\e;
use function League\Plates\p;

abstract class AlpineJs
{
    public static function Component($data, $children) {
        return p(function() use ($data, $children) {
            ?>  <div x-data="<?=e(json_encode($data))?>">
                <?=p($children)?>
            </div> <?php
        });
    }
}
