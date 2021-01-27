<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\UI\ListingQueryParams;
use Doctrine\Common\Collections\Collection;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Table;
use League\Plates\Component;
use League\Plates\Extension\Pagination\ItemPage;
use League\Plates\Extension\Pagination\Pagination;
use function Krak\Admin\Templates\Form\HiddenInput;
use function Krak\Admin\Templates\Form\SelectInput;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Typography\Button;
use function Krak\Admin\Templates\Typography\ButtonLink;
use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\attrs;
use function League\Plates\Extension\Symfony\csrfToken;
use function League\Plates\Extension\Symfony\path;
use function League\Plates\classNames;
use function League\Plates\h;
use function League\Plates\p;
use Krak\Fun\{f, c};

final class SizeScaleListPage extends Component
{
    private $sizeScales;
    private $params;

    /** @param SizeScale[] */
    public function __construct(Collection $sizeScales, ListingQueryParams $params) {
        $this->sizeScales = $sizeScales;
        $this->params = $params;
    }

    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
            $totalResults = count($this->sizeScales);
        ?>
          <?=PageTitle('Size Scales | List')?>
          <div class="flex justify-between items-center my-4">
            <form method="get" class="flex space-x-2">
              <?=p([
                TextInput('search', $this->params->search(), ['placeholder' => 'Search']),
                SelectInput('pageSize', [
                    ['5', 'Per Page: 5'],
                    ['10', 'Per Page: 10'],
                    ['25', 'Per Page: 25'],
                    ['50', 'Per Page: 50'],
                    ['100', 'Per Page: 100'],
                    ['200', 'Per Page: 200'],
                ], (string) $this->params->pageSize(), ['class' => ['w-full' => null,]]),
                Button('Submit', 'success', ['type' => 'submit']),
                HiddenInput('page', $this->params->page()),
              ])?>
            </form>
            <div class="space-x-2 text-right">
              <?=p([ButtonLink('Add Size Scale', path('catalog_size_scale_admin_create'), 'success')])?>
            </div>
          </div>
          <?=Table::WrappedTable([
            Table::Thead([
              Table::Th('Id'),
              Table::Th('Name'),
              Table::Th('Status'),
              Table::Th('Sizes'),
              Table::Th(h('div', 'Total Results: ' . $totalResults, ['class' => 'text-right'])),
            ]),
            Table::Tbody($this->TableBody())
          ])?>
          <?=h('div', $this->Pagination($this->params->page(), $this->params->pageSize(), $totalResults), ['class' => 'mt-4 text-center'])?>
        <?php
        }))->title('Size Scales | List');
    }

    private function TableBody() {
        return !$this->sizeScales->isEmpty() ? f\map(function(SizeScale $sizeScale) {
            return Table::Tr([
                Table::Td($sizeScale->id()),
                Table::Td(self::SearchHighlight($this->params->search(), $sizeScale->name())),
                Table::Td(self::SearchHighlight($this->params->search(), $sizeScale->status())),
                Table::Td(PresentedSizeScale::csvSizes($sizeScale)),
                Table::Td(h('div', [
                    TextLink('View', path('catalog_size_scale_admin_view', ['id' => $sizeScale->id()])),
                    TextLink('Edit', path('catalog_size_scale_admin_edit', ['id' => $sizeScale->id()])),
                    h(
                        'div',
                        self::FormLink('Delete', path('catalog_size_scale_admin_delete', ['id' => $sizeScale->id()]), 'delete'),
                        ['class' => 'inline-block']
                    ),
                ], ['class' => 'text-right space-x-2'])),
            ]);
        }, $this->sizeScales) : Table::Tr([Table::Td('No Results', ['class' => 'text-center text-gray-400', 'colspan' => 5])]);
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
            echo preg_replace_callback('/'. preg_quote($search)  .'/i', function(array $matches) {
                return h('span', $matches[0], ['class' => 'bg-yellow-200']);
            }, p($children));
        }) : p($children);
    }

    private function Pagination(int $page, int $pageSize, int $maxResults) {
        return Pagination::fromTotalResults($maxResults, $page, $pageSize, function(ItemPage $itemPage) {
            return h(($itemPage->disabled() || $itemPage->selected()) ? 'span' : 'a', $itemPage->title(), [
                'class' => [
                    'px-3 py-2',
                    $itemPage->selected() ? 'text-pink-400 cursor-default' : null,
                    $itemPage->disabled() ? 'text-blue-200 cursor-default' : null,
                    !$itemPage->selected() && !$itemPage->disabled() ? 'text-blue-400 hover:text-blue-500 underline cursor-pointer' : null],
                'href' => path('catalog_size_scale_admin_list', array_merge($this->params->toArray(), ['page' => $itemPage->page()]))
            ]);
        }, ['class' => 'inline-flex divide-x divide-gray-200 text-center justify-center bg-white rounded-md shadow']);
    }
}
