<?php

namespace Krak\Admin\Templates\Form;

use function League\Plates\attrs;
use function League\Plates\Extension\Symfony\flashes;
use function League\Plates\escape;
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

function TagsInput(string $fieldName, array $tags = [], ...$attrs) {
    return p(function() use ($fieldName, $tags, $attrs) {
        $tags = json_encode($tags);
        $tagJs = escape(
            /** @lang JavaScript */ <<<JavaScript
(function() {
    return {
        tags: {$tags},
        onEnter: function(event) {
            event.preventDefault();
            if (!event.target.value) {
                return;
            }
            if (this.tags.includes(event.target.value)) {
                return;
            }
  
            this.tags.push(event.target.value);
            event.target.value = '';
        },
        onBackspace: function(event) {
            if (!event.target.value) {
                this.tags.pop();
            }
        },
        onRemoveElement: function(event, tag) {
            this.tags.splice(this.tags.indexOf(tag), 1);
        }
      }
  })();
JavaScript
        );
      ?>
          <div
              x-data="<?=$tagJs?>"
              class="focus-within:ring-pink-500 focus-within:border-pink-500 block w-full sm:text-sm border border-gray-300 rounded-md pr-3 pb-2 flex flex-wrap">
              <template x-for="(tag, index) in tags">
                <span class="self-center rounded-md px-2 py-1 bg-pink-400 text-white inline-block ml-3 mt-2">
                  <span x-text="tag"></span>
                  <input type="hidden" name="<?=$fieldName?>" :value="tag"/>
                  <span class="text-pink-100 text-xs cursor-pointer" @click="onRemoveElement($event, tag)">x</span>
                </span>
              </template>
            <input
              type="text"
              class="flex-1 border-0 rounded-md focus:ring-0 sm:text-sm p-0 ml-3 mt-2"
              @keydown.enter="onEnter($event)"
              @keydown.tab="onEnter($event)"
              @keydown.backspace="onBackspace($event)"/>
          </div>
      <?php
    });
}
