<?php

namespace Krak\Admin\Templates\Crud;

use Krak\Admin\Form\DataAccess;
use Krak\Admin\Form\Form;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Typography;
use function League\Plates\attrs;
use function League\Plates\Bridge\Symfony\flashes;
use function League\Plates\Bridge\Symfony\url;
use function League\Plates\p;

final class CrudCreatePage
{
    private $form;
    private $dataAccess;

    public function __construct(Form $form, ?callable $dataAccess = null) {
        $this->form = $form;
        $this->dataAccess = $dataAccess ?: DataAccess::alwaysEmpty();
    }

    public function __invoke(): void {
        $title = $this->form->name() . ' | Create';
        echo p((new OneColumnLayout(function() use ($title) {
        ?>  <?=p(Typography::PageTitle($title))?>
            <?=p(self::FlashMessages())?>
            <form class="grid grid-cols-4 gap-4" method="POST">
              <?php foreach ($this->form->fields() as $field): ?>
                <?=p(self::FormElement(function() use ($field) {
                  ?>
                    <?=p(self::Label($field->nameForDisplay()))?>
                    <?=p(self::TextInput($field->name(), ($this->dataAccess)($field->name()), $field->isRequired() ? [
                        'required' => null,
                      ] : []))?>
                  <?php
                }))?>
              <?php endforeach; ?>
              <div class="col-span-4">
                <?=p(Typography::textLink('Back', url('home'), 'pr-2'))?>
                <?=p(Typography::buttonSuccess('Create', ['type' => 'submit', 'class' => 'px-4 py-1 ml-2']))?>
              </div>
            </form>
            <?php
        }))
            ->title($title));
    }

    private static function FlashMessages() {
        return function() {
          ?>
            <?=p(self::FlashMessagesForType('success', 'bg-green-400', 'text-white', 'hover:text-green-100'))?>
            <?=p(self::FlashMessagesForType('warning', 'bg-yellow-400', 'text-white', 'hover:text-yellow-100'))?>
            <?=p(self::FlashMessagesForType('error', 'bg-red-400', 'text-white', 'hover:text-red-100'))?>
          <?php
        };
    }

    private static function FlashMessagesForType(string $type, string $mainBg, string $textColor, string $hoverDismissTextColor) {
        return function() use ($type, $mainBg, $textColor, $hoverDismissTextColor) {
            $messages = flashes()->get($type);
            if (!$messages) {
                return;
            }

        ?>  <div class="rounded-md space-y-1 <?=$textColor?> <?=$mainBg?> p-2 relative" x-data="{ closed: false }" :class="{ hidden: closed }">
              <?php foreach ($messages as $message): ?>
                <p><?=$message?></p>
              <?php endforeach; ?>
              <p class="text-xs underline <?=$hoverDismissTextColor?> cursor-pointer" @click="closed = true">Dismiss</p>
            </div> <?php
        };
    }

    private static function FormElement($children, $attrs = []) {
        return function() use ($children, $attrs) {
        ?>  <div <?=attrs(['class' => 'align-middle col-span-2'], $attrs)?>>
              <label class="flex-col items-center w-full space-y-1">
                  <?=p($children)?>
              </label>
            </div> <?php
        };
    }

    private static function Label(string $title) {
        return function() use ($title) {
            ?> <span class="whitespace-nowrap inline-block text-gray-900 font-medium"><?=$title?></span> <?php
        };
    }

    private static function TextInput(string $name, ?string $value = null, array $attrs = []) {
        return function() use ($name, $value, $attrs) {
            ?> <input <?=attrs($attrs)?> type="text" name="<?=$name?>" value="<?=$value?>" class="focus:ring-pink-500 focus:border-pink-500 block w-full sm:text-sm border-gray-300 rounded-md"/> <?php
        };
    }
}
