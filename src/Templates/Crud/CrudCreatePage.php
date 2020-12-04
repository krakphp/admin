<?php

namespace Krak\Admin\Templates\Crud;

use Krak\Admin\Form\Field;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Typography;
use function League\Plates\attrs;
use function League\Plates\Bridge\Symfony\url;
use function League\Plates\p;

final class CrudCreatePage
{
    private $fields;

    /** @param Field[] $fields */
    public function __construct(array $fields) {
        $this->fields = $fields;
    }

    public function __invoke(): void {
        echo p((new OneColumnLayout(function() {
        ?>  <h1 class="font-semibold text-2xl">Some Entity | Create</h1>
            <form class="px-2 grid grid-cols-2">
                <?=p(self::FormGroup(function() {
                  ?>
                      <label class="block font-semibold" for="field1">Field 1:</label>
                      <input type="text" name="field1" class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full sm:text-sm border-pink-400 rounded-md">
                      <?php
                }))?>
                <?=p(self::FormGroup(function() {
                    ?> <label>
                    Field 2:
                    <input type="text" name="field2" />
                  </label> <?php
                }))?>
                <div>
                    <?=p(Typography::textLink('Back', url('home'), 'pr-2'))?>
                    <?=p(Typography::buttonSuccess('Create', ['type' => 'submit', 'class' => 'px-4 py-1 ml-2']))?>
                </div>
            </form>
            <?php
        }))
            ->title('Some Entity | Create'));
    }

    private static function FormGroup($children, $attrs = []) {
        return function() use ($children, $attrs) {
            ?> <div <?=attrs(['class' => 'my-2 flex align-middle col-span-2'], $attrs)?>><?=p($children)?></div> <?php
        };
    }
}
