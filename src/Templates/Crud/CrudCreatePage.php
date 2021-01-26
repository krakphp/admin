<?php

namespace Krak\Admin\Templates\Crud;

use Krak\Admin\Form\DataAccess;
use Krak\Admin\Form\Form;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Typography;
use function Krak\Admin\Templates\Form\FlashMessages;
use function League\Plates\attrs;
use function League\Plates\Extension\Symfony\flashes;
use function League\Plates\Extension\Symfony\url;
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
        echo (new OneColumnLayout(function() use ($title) {
        ?>  <?=Typography::PageTitle($title)?>
            <?=FlashMessages()?>
            <form class="grid grid-cols-4 gap-4" method="POST">
              <?php foreach ($this->form->fields() as $field): ?>
                <?=self::FormElement(function() use ($field) {
                  ?>
                    <?=self::Label($field->nameForDisplay())?>
                    <?=self::TextInput($field->name(), ($this->dataAccess)($field->name()), $field->isRequired() ? [
                        'required' => null,
                      ] : [])?>
                  <?php
                })?>
              <?php endforeach; ?>
              <div class="col-span-4">
                <?=Typography::textLink('Back', url('home'), 'pr-2')?>
                <?=Typography::buttonSuccess('Create', ['type' => 'submit', 'class' => 'px-4 py-1 ml-2'])?>
              </div>
            </form>
            <?php
        }))
            ->title($title);
    }

    private static function FormElement($children, $attrs = []) {
        return p(function() use ($children, $attrs) {
        ?>  <div <?=attrs(['class' => 'align-middle col-span-2'], $attrs)?>>
              <label class="flex-col items-center w-full space-y-1">
                  <?=p($children)?>
              </label>
            </div> <?php
        });
    }

    private static function Label(string $title) {
        return p(function() use ($title) {
            ?> <span class="whitespace-nowrap inline-block text-gray-900 font-medium"><?=$title?></span> <?php
        });
    }

    private static function TextInput(string $name, ?string $value = null, array $attrs = []) {
        return p(function() use ($name, $value, $attrs) {
            ?> <input <?=attrs($attrs)?> type="text" name="<?=$name?>" value="<?=$value?>" class="focus:ring-pink-500 focus:border-pink-500 block w-full sm:text-sm border-gray-300 rounded-md"/> <?php
        });
    }
}
