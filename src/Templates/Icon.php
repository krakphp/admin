<?php

namespace Krak\Admin\Templates;

use function League\Plates\p;

final class Icon
{
    public static function Close(string $classes) {
        return p(function() use ($classes) {
        ?>  <svg class="<?=$classes?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg> <?php
        });
    }
}
