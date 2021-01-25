<?php

namespace Krak\Admin\Templates;

use function League\Plates\attrs;
use function League\Plates\h;
use function League\Plates\p;

final class Table
{
    public static function Wrapper($children, ...$attrs) {
        return p(function() use ($children, $attrs) {
        ?>  <div <?=attrs(['class' => "flex flex-col"], ...$attrs)?>>
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <?=p($children)?>
                    </div>
                </div>
            </div> <?php
        });
    }

    public static function WrappedTable($children, ...$attrs) {
        return self::Wrapper(self::Table($children), ...$attrs);
    }

    public static function Table($children) {
        return p(function() use ($children) {
        ?>
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-pink-200">
              <?=p($children)?>
            </table>
          </div>
        <?php
        });
    }

    public static function Thead($children) {
        return p(function() use ($children) {
          ?> <thead><tr><?=p($children)?></tr></thead> <?php
        });
    }

    public static function Tbody($children, ...$attrs) {
        return h('tbody', $children, ['class' => 'bg-white divide-y divide-gray-200'], ...$attrs);
    }

    public static function Th($children, ...$attrs) {
        return h('th', $children, [
            'class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-medium text-pink-500 uppercase tracking-wider',
            'scope' => 'col'
        ], ...$attrs);
    }

    public static function Tr($children, ...$attrs) {
        return h('tr', $children, ...$attrs);
    }

    public static function Td($children, ...$attrs) {
        return h('td', $children, ['class' => 'px-6 py-4 whitespace-nowrap'], ...$attrs);
    }
}
