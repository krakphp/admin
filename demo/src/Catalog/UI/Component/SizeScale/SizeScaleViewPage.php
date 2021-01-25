<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Component;
use function Krak\Admin\Templates\Typography\{Button, ButtonLink, Card, DefinitionList, DefinitionListItem, PageTitle};
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
            <?=Card(DefinitionList([
                DefinitionListItem('Id', $this->sizeScale->id()),
                DefinitionListItem('Name', $this->sizeScale->name()),
                DefinitionListItem('Status', $this->sizeScale->status()),
            ]), ['class' => 'mt-4 max-w-lg'])?>
            <div class="mt-4 space-x-2"><?=p([
                ButtonLink('Back', path('catalog_size_scale_admin_list')),
                ButtonLink('Edit', '#', 'success'),
            ])?></div>
        <?php
        }))->title($title);
    }
}
