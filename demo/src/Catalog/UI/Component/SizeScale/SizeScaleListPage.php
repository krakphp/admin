<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Table;
use League\Plates\Component;
use function Krak\Admin\Templates\Typography\ButtonLink;
use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\Bridge\Symfony\csrfToken;
use function League\Plates\Bridge\Symfony\path;
use function League\Plates\classNames;
use function League\Plates\h;
use function League\Plates\p;

final class SizeScaleListPage extends Component
{
    private $sizeScales;

    /** @param SizeScale[] */
    public function __construct(array $sizeScales) {
        $this->sizeScales = $sizeScales;
    }

    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
        ?>
          <?=PageTitle('Size Scales | List')?>
          <div class="space-x-2 text-right my-4">
            <?=p([ButtonLink('Add Size Scale', path('catalog_size_scale_admin_create'), 'success')])?>
          </div>
          <?=Table::WrappedTable([
            Table::Thead([
              Table::Th('Id'),
              Table::Th('Name'),
              Table::Th('Status'),
              Table::Th(function() { ?> <span class="sr-only">Edit</span> <?php }),
            ]),
            Table::Tbody($this->TableBody())
          ])?>
        <?php
        }))->title('Size Scales | List');
    }

    private function TableBody() {
        return $this->sizeScales ? array_map(function(SizeScale $sizeScale) {
            return Table::Tr([
                Table::Td($sizeScale->id()),
                Table::Td($sizeScale->name()),
                Table::Td($sizeScale->status()),
                Table::Td(h('div', [
                    TextLink('View', path('catalog_size_scale_admin_view', ['id' => $sizeScale->id()])),
                    TextLink('Edit', '#'),
                    self::FormLink('Delete', path('catalog_size_scale_admin_delete', ['id' => $sizeScale->id()]), 'delete'),
//                    TextLink('Delete', '#', [
//                        'class' => classNames('text-red-400 hover:text-red-500', ['text-blue-400' => false, 'hover:text-blue-500' => false])
//                    ])
                ], ['class' => 'space-x-2 text-right'])),
            ]);
        }, $this->sizeScales) : Table::Tr([Table::Td('No Results', ['class' => 'text-center text-gray-400', 'colspan' => 4])]);
    }

    private static function FormLink(string $title, string $action, string $method) {
        return p(function() use ($title, $action, $method) {
        ?>
          <form action="<?=$action?>" method="post" class="inline-block">
            <input type="hidden" name="_method" value="<?=$method?>"/>
            <input type="hidden" name="_token" value="<?=csrfToken('delete-size-scale')?>"/>
            <button class="text-red-400 hover:text-red-500 underline" type="submit"><?=p($title)?></button>
          </form>
        <?php
        });
    }
}
