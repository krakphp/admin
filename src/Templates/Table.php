<?php

namespace Krak\Admin\Templates;

use function League\Plates\p;

final class Table
{
    public static function Wrapper($children) {
        return p(function() use ($children) {
            ?>  <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <?=p($children)?>
                    </div>
                </div>
            </div> <?php
        });
    }

    public static function Th($children) {
        return p(function() use ($children) {
            ?>  <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-pink-500 uppercase tracking-wider">
                <?=p($children)?>
            </th> <?php
        });
    }

    public static function Td($children) {
        return p(function() use ($children) {
            ?>  <td class="px-6 py-4 whitespace-nowrap">
                <?=p($children)?>
            </td> <?php
        });
    }
}
