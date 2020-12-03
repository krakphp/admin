<?php

namespace Krak\Admin\Templates;

use function League\Plates\attrs;
use function League\Plates\p;

final class Typography
{
    public static function textLink($content, string $href, string $classes = ''): callable {
        return function() use ($content, $href, $classes) {
            ?> <a class="text-blue-400 hover:text-blue-500 underline <?=$classes?>" href="<?=$href?>"><?=p($content)?></a> <?php
        };
    }

    public static function button($content, $attrs = []) {
        return function() use ($content, $attrs) {
            ?> <button <?=attrs(['type' => 'button', 'class' => 'rounded-sm hover:underline'], $attrs)?>><?=p($content)?></button> <?php
        };
    }

    public static function buttonSuccess($content, $attrs = []) {
        return function() use ($content, $attrs) {
            ?> <?=p(self::button($content, attrs(['class' => 'bg-green-400 hover:bg-green-500 text-white'], $attrs)))?> <?php
        };
    }
}
