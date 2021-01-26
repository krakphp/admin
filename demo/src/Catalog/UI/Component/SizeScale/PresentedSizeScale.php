<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use League\Plates\Component;
use function League\Plates\p;
use Krak\Fun\{f, c};

abstract class PresentedSizeScale
{
    public static function csvSizes(SizeScale $sizeScale): Component {
        return p(f\join(', ', self::sortedSizes($sizeScale)));
    }

    public static function sortedSizes(SizeScale $sizeScale): array {
        $sizes = f\arrayMap(c\method('size'), $sizeScale->sizes());
        sort($sizes);
        return $sizes;
    }
}
