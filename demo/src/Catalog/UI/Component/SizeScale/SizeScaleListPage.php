<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\UI\ListingQueryParams;
use Demo\App\Catalog\UI\SortTuple;
use Doctrine\Common\Collections\Collection;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Table;
use Krak\Admin\Templates\Typography\FormLink;
use League\Plates\Component;
use League\Plates\Extension\Heroicons\OutlineIcon;
use League\Plates\Extension\Pagination\ItemPage;
use League\Plates\Extension\Pagination\Pagination;
use function Krak\Admin\Templates\Form\HiddenInput;
use function Krak\Admin\Templates\Form\SelectInput;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Typography\Button;
use function Krak\Admin\Templates\Typography\ButtonLink;
use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\Extension\Symfony\path;
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
                Button('Go', 'success', ['type' => 'submit']),
                HiddenInput('page', $this->params->page()),
                $this->params->sort() ? HiddenInput('sort', $this->params->sort()) : null,
              ])?>
            </form>
            <div class="space-x-2 text-right">
              <?=p([ButtonLink('Add Size Scale', path('catalog_size_scale_admin_create'), 'success')])?>
            </div>
          </div>
          <?=Table::WrappedTable([
            Table::Thead([
              Table::Th($this->SortableHeader('Id')),
              Table::Th($this->SortableHeader('Name')),
              Table::Th($this->SortableHeader('Status')),
              Table::Th('Root Version Id'),
              Table::Th('Sizes'),
              Table::Th(h('div', 'Total Results: ' . $totalResults, ['class' => 'text-right'])),
            ]),
            Table::Tbody($this->TableBody())
          ])?>
          <?=h('div', $this->Pagination($this->params->page(), $this->params->pageSize(), $totalResults), ['class' => 'mt-4 text-center'])?>
        <?php
        }))->titleAndPageTitle('Size Scales | List');
    }

    private function TableBody() {
        return !$this->sizeScales->isEmpty() ? f\map(function(SizeScale $sizeScale) {
            return Table::Tr([
                Table::Td($sizeScale->id()),
                Table::Td(self::SearchHighlight($this->params->search(), $sizeScale->name())),
                Table::Td(self::SearchHighlight($this->params->search(), $sizeScale->status())),
                Table::Td(self::SearchHighlight($this->params->search(), $sizeScale->rootVersionId())),
                Table::Td(PresentedSizeScale::csvSizes($sizeScale)),
                Table::Td(h('div', [
                    TextLink('View', path('catalog_size_scale_admin_view', ['id' => $sizeScale->id()])),
                    TextLink('Edit', path('catalog_size_scale_admin_edit', ['id' => $sizeScale->id()])),
                    FormLink::delete(path('catalog_size_scale_admin_delete', ['id' => $sizeScale->id()]), function() {
                      ?> <button class="text-red-400 hover:text-red-500 underline" type="submit">Delete</button> <?php
                    }, ['class' => 'inline-block']),
                ], ['class' => 'space-x-2 flex justify-end'])),
            ]);
        }, $this->sizeScales) : Table::Tr([Table::Td('No Results', ['class' => 'text-center text-gray-400', 'colspan' => 6])]);
    }

    private static function SearchHighlight(?string $search, $children) {
        return $search ? p(function() use ($search, $children) {
            echo preg_replace_callback('/'. preg_quote($search)  .'/i', function(array $matches) {
                return h('span', $matches[0], ['class' => 'bg-yellow-200']);
            }, p($children));
        }) : p($children);
    }

    private function SortableHeader(string $title, ?string $field = null) {
        $field = $field ?: strtolower($title);
        $sortTuple = $this->params->sortTuple();
        $nextSort = $sortTuple->field() === $field ? $sortTuple->cycle() : SortTuple::new($field);
        $attrsForIcon = ['class' => 'w-4 inline-block align-text-top'];
        return h('a', [
            h('span', $title),
            $sortTuple->field() === $field
                ? ($sortTuple->isAsc() ? OutlineIcon::ArrowUp($attrsForIcon) : OutlineIcon::ArrowDown($attrsForIcon))
                : OutlineIcon::SwitchVertical($attrsForIcon)
        ], [
            'class' => 'cursor-pointer',
            'href' => path('catalog_size_scale_admin_list', array_merge($this->params->toArray(), ['sort' => $nextSort->toString()]))
        ]);
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
