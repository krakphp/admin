<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Doctrine\Common\Collections\Collection;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Table;
use League\Plates\Component;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Typography\ButtonLink;
use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\Extension\Symfony\csrfToken;
use function League\Plates\Extension\Symfony\path;
use function League\Plates\classNames;
use function League\Plates\h;
use function League\Plates\p;
use Krak\Fun\{f, c};

final class SizeScaleListPage extends Component
{
    private $sizeScales;
    private $search;

    /** @param SizeScale[] */
    public function __construct(Collection $sizeScales, ?string $search = null) {
        $this->sizeScales = $sizeScales;
        $this->search = $search;
    }

    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
        ?>
          <?=PageTitle('Size Scales | List')?>

          <div class="flex justify-between items-center">
            <form method="get">
              <?=TextInput('search', $this->search, ['placeholder' => 'Search'])?>
            </form>
            <div class="space-x-2 text-right my-4">
              <?=p([ButtonLink('Add Size Scale', path('catalog_size_scale_admin_create'), 'success')])?>
            </div>
          </div>
          <?=Table::WrappedTable([
            Table::Thead([
              Table::Th('Id'),
              Table::Th('Name'),
              Table::Th('Status'),
              Table::Th('Sizes'),
              Table::Th(function() { ?> <span class="sr-only">Edit</span> <?php }),
            ]),
            Table::Tbody($this->TableBody())
          ])?>
          <?=h('div', self::Pagination(1, 20, 7), ['class' => 'mt-4'])?>
        <?php
        }))->title('Size Scales | List');
    }

    private function TableBody() {
        return $this->sizeScales->isEmpty() ? f\map(function(SizeScale $sizeScale) {
            return Table::Tr([
                Table::Td($sizeScale->id()),
                Table::Td(self::SearchHighlight($this->search, $sizeScale->name())),
                Table::Td(self::SearchHighlight($this->search, $sizeScale->status())),
                Table::Td(PresentedSizeScale::csvSizes($sizeScale)),
                Table::Td(h('div', [
                    TextLink('View', path('catalog_size_scale_admin_view', ['id' => $sizeScale->id()])),
                    TextLink('Edit', path('catalog_size_scale_admin_edit', ['id' => $sizeScale->id()])),
                    self::FormLink('Delete', path('catalog_size_scale_admin_delete', ['id' => $sizeScale->id()]), 'delete'),
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

    private static function SearchHighlight(?string $search, $children) {
        return $search ? p(function() use ($search, $children) {
            echo preg_replace_callback('/'. preg_quote($search)  .'/', function(array $matches) {
                return h('span', $matches[0], ['class' => 'bg-yellow-200']);
            }, p($children));
        }) : p($children);
    }

    private static function Pagination(int $page, int $pageSize, int $maxResults) {
        return new class($page, $pageSize, $maxResults) extends Component
        {
            private $page;
            private $pageSize;
            private $maxResults;

            public function __construct(int $page, int $pageSize, int $maxResults) {
                $this->page = $page;
                $this->pageSize = $pageSize;
                $this->maxResults = $maxResults;
            }

            public function __invoke(): void {
            ?>
                <div class="text-center">
                    <div class="inline-flex divide-x divide-gray-200 text-center justify-center bg-white rounded-md shadow">
                    <?=p([
                        self::Item(TextLink('First', '#')),
                        self::Item(TextLink('Prev', '#')),
                        self::Item(TextLink('1', '#')),
                        self::Item(TextLink('2', '#', ['class' => classNames('text-pink-300 cursor-default', ['text-blue-400' => null, 'hover:text-blue-500' => null, 'underline' => null])])),
                        self::Item(TextLink('3', '#')),
                        self::Item(TextLink('Next', '#')),
                        self::Item(TextLink('Last', '#')),
                    ])?>
                    </div>
                </div>
            <?php
            }

            private static function Item($children, bool $selected = false, ...$attrs) {
                return h('span', $children, ['class' => 'px-3 py-2 cursor-pointer'], ...$attrs);
            }
        };
    }
}
