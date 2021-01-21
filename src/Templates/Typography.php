<?php

namespace Krak\Admin\Templates;

use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\attrs;
use function League\Plates\p;

final class Typography
{
    public static function PageTitle(string $title) {
        return PageTitle($title);
    }

    public static function textLink($content, string $href, string $classes = ''): callable {
        return TextLink($content, $href, ['class' => $classes]);
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
