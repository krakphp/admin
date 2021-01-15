<?php

namespace Krak\Admin\Templates;

use function League\Plates\attrs;
use function League\Plates\p;

final class Typography
{
    public static function PageTitle(string $title) {
        return p(function() use ($title) {
            ?> <h1 class="font-medium text-2xl text-gray-900 mb-4"><?=$title?></h1> <?php
        });
    }

    public static function textLink($content, string $href, string $classes = ''): callable {
        return p(function() use ($content, $href, $classes) {
            ?> <a class="text-blue-400 hover:text-blue-500 underline <?=$classes?>" href="<?=$href?>"><?=$content?></a> <?php
        });
    }

    public static function buttonSuccess($content, $attrs = []) {
        return p(function() use ($content, $attrs) {
            ?> <?=self::button($content, attrs(['class' => 'bg-green-400 hover:bg-green-500 text-white'], $attrs))?> <?php
        });
    }

    public static function Button(string $title, string $type = 'info', array $attrs = []) {
        return p(function() use ($title, $type, $attrs) {
            switch ($type) {
            case 'success': $classes = 'bg-green-400 hover:bg-green-500'; break;
            case 'info':
            default: $classes = 'bg-blue-500 hover:bg-blue-600'; break;
            }

            ?> <button class="rounded-md hover:underline text-sm <?=$classes?> text-white px-4 py-2" <?=attrs($attrs)?>><?=$title?></button> <?php
        });
    }
}
