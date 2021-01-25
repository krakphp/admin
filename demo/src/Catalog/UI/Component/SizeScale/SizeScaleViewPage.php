<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Component;
use function Krak\Admin\Templates\Typography\{Button, ButtonLink, Card, DefinitionList, DefinitionListItem, PageTitle,
    TextLink};
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
            <?=Card([
                DefinitionList([
                    DefinitionListItem('Id', $this->sizeScale->id()),
                    DefinitionListItem('Name', $this->sizeScale->name()),
                    DefinitionListItem('Status', $this->sizeScale->status()),
                ]),
                h('div', [
                    TextLink('Show All', path('catalog_size_scale_admin_list')),
                    TextLink('Edit', path('catalog_size_scale_admin_edit', ['id' => $this->sizeScale->id()]))
                ], ['class' => 'space-x-2 mt-4'])
            ], ['class' => 'mt-4 max-w-lg p-4'])?>
        <?php
        }))->title($title);
    }
}
