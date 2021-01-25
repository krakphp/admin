<?php

namespace Krak\Admin\Templates\Form;

use function League\Plates\attrs;
use function League\Plates\Bridge\Symfony\flashes;
use function League\Plates\h;
use function League\Plates\p;

function FlashMessages() {
    return p([
        FlashMessagesForType('success', 'bg-green-400', 'text-white', 'hover:text-green-100'),
        FlashMessagesForType('warning', 'bg-yellow-400', 'text-white', 'hover:text-yellow-100'),
        FlashMessagesForType('error', 'bg-red-400', 'text-white', 'hover:text-red-100'),
    ]);
}

function FlashMessagesForType(string $type, string $mainBg, string $textColor, string $hoverDismissTextColor) {
    return p(function() use ($type, $mainBg, $textColor, $hoverDismissTextColor) {
        $messages = flashes()->get($type);
        if (!$messages) {
            return;
        }
    ?>
        <div class="rounded-md space-y-1 <?=$textColor?> <?=$mainBg?> p-2 relative" x-data="{ closed: false }" :class="{ hidden: closed }">
          <?php foreach ($messages as $message): ?>
            <p><?=p($message)?></p>
          <?php endforeach; ?>
          <p class="text-xs underline <?=$hoverDismissTextColor?> cursor-pointer" @click="closed = true">Dismiss</p>
        </div>
    <?php
    });
}

function FormElement($children, ...$attrs) {
    return p(function() use ($children, $attrs) {
    ?>  <div <?=attrs(['class' => 'align-middle col-span-2'], ...$attrs)?>>
          <label class="flex-col items-center w-full space-y-1">
              <?=p($children)?>
          </label>
        </div> <?php
    });
}

function Label($title, ...$attrs) {
    return h('span', $title, ['class' => 'whitespace-nowrap inline-block text-gray-900 font-medium']);
}

function TextInput(string $name, ?string $value = null, ...$attrs) {
    return h('input', null, [
        'name' => $name,
        'value' => $value,
        'type' => 'text',
        'class' => 'focus:ring-pink-500 focus:border-pink-500 block w-full sm:text-sm border-gray-300 rounded-md'
    ], ...$attrs);
}
