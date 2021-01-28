<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Typography\FormLink;
use League\Plates\Component;
use function Krak\Admin\Templates\Typography\{Button, ButtonLink, Card, DefinitionList, DefinitionListItem, PageTitle,
    TextLink};
use function Krak\Admin\Templates\Form\FlashMessages;
use function League\Plates\Extension\Symfony\path;
use function League\Plates\{p, h};

final class SizeScaleViewPage extends Component
{
    private $sizeScale;

    public function __construct(SizeScale $sizeScale) {
        $this->sizeScale = $sizeScale;
    }

    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
        ?>
            <?=FlashMessages()?>
            <?=Card([
                DefinitionList([
                    DefinitionListItem('Id', $this->sizeScale->id()),
                    DefinitionListItem('Name', $this->sizeScale->name()),
                    DefinitionListItem('Status', $this->sizeScale->status()),
                    DefinitionListItem('Sizes', PresentedSizeScale::csvSizes($this->sizeScale))
                ]),
                h('div', [
                    TextLink('Show All', path('catalog_size_scale_admin_list')),
                    TextLink('Edit', path('catalog_size_scale_admin_edit', ['id' => $this->sizeScale->id()])),
                    FormLink::post(path('catalog_size_scale_admin_publish', ['id' => $this->sizeScale->id()]), function() {
                        ?> <button class="text-blue-400 hover:text-blue-500 underline" type="submit">Publish</button><?php
                    })
                ], ['class' => 'space-x-2 mt-4 flex items-center'])
            ], ['class' => 'mt-4 max-w-lg p-4'])?>
        <?php
        }))->titleAndPageTitle('Size Scales | ' . $this->sizeScale->name());
    }
}
