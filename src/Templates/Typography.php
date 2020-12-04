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

    public static function buttonSuccess($content, $attrs = []) {
        return function() use ($content, $attrs) {
            ?> <?=p(self::button($content, attrs(['class' => 'bg-green-400 hover:bg-green-500 text-white'], $attrs)))?> <?php
        };
    }

    public static function Button(string $title, string $type = 'info', array $attrs = []) {
        return function() use ($title, $type, $attrs) {
            switch ($type) {
            case 'success': $classes = 'bg-green-400 hover:bg-green-500'; break;
            case 'info':
            default: $classes = 'bg-blue-500 hover:bg-blue-600'; break;
            }

            ?> <button class="rounded-md hover:underline text-sm <?=$classes?> text-white px-4 py-2" <?=attrs($attrs)?>><?=$title?></button> <?php
        };
    }
}
