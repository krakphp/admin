<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Component;
use function Krak\Admin\Templates\Typography\{Button, ButtonLink, PageTitle};
use function League\Plates\Bridge\Symfony\path;
use function League\Plates\{p, h};

final class SizeScaleViewPage extends Component
{
    private $sizeScale;

    public function __construct(SizeScale $sizeScale) {
        $this->sizeScale = $sizeScale;
    }

    public function __invoke(): void {
        $title = 'Size Scales | ' . $this->sizeScale->name();
        echo (new OneColumnLayout(function() use ($title) {
        ?>
            <?=PageTitle($title)?>
            <?=self::Card(self::DefinitionList([
                self::DefinitionListItem('Id', $this->sizeScale->id()),
                self::DefinitionListItem('Name', $this->sizeScale->name()),
                self::DefinitionListItem('Status', $this->sizeScale->status()),
            ]), ['class' => 'mt-4 max-w-lg'])?>
            <div class="mt-4 space-x-2"><?=p([
                ButtonLink('Back', path('catalog_size_scale_admin_list')),
                ButtonLink('Edit', '#', 'success'),
            ])?></div>
        <?php
        }))->title($title);
    }

    private static function DefinitionList($items, ...$attrs) {
        return h('dl', $items, ['class' => 'divide-y divide-gray-200'], ...$attrs);
    }

    private static function DefinitionListItem($term, $definition) {
        return h('div', [
            h('dt', $term, ['class' => 'text-pink-300']),
            h('dd', $definition, ['class' => 'col-span-2'])
        ], ['class' => 'grid grid-cols-3 gap-4 p-4']);
    }

    private static function Card($children, ...$attrs) {
        return h('div', $children, ['class' => 'bg-white sm:rounded-lg shadow'], ...$attrs);
    }
}
