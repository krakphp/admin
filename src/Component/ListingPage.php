<?php

namespace Krak\Admin\Component;

use Demo\App\Catalog\UI\ListingQueryParams;
use Demo\App\Catalog\UI\SortTuple;
use Doctrine\Common\Collections\Collection;
use Krak\Admin\ListingDefinition;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Table;
use League\Plates\Component;
use League\Plates\Extension\Heroicons\OutlineIcon;
use League\Plates\Extension\Pagination\ItemPage;
use League\Plates\Extension\Pagination\Pagination;
use function Krak\Admin\Templates\Form\HiddenInput;
use function Krak\Admin\Templates\Form\SearchHighlight;
use function Krak\Admin\Templates\Form\SelectInput;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Form\WrapNulls;
use function Krak\Admin\Templates\Typography\Button;
use function League\Plates\h;
use function League\Plates\p;
use Krak\Fun\{f, c};

final class ListingPage extends Component
{
    private $listingDefinition;
    private $items;
    private $params;

    public function __construct(ListingDefinition $listingDefinition, Collection $items, ListingQueryParams $params) {
        $this->listingDefinition = $listingDefinition;
        $this->items = $items;
        $this->params = $params;
    }

    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
            $totalResults = count($this->items);
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

                </div>
            </div>
            <?=Table::WrappedTable([
                $this->Thead($totalResults),
                Table::Tbody($this->TableBody())
            ])?>
            <?=h('div', $this->Pagination($this->params->page(), $this->params->pageSize(), $totalResults), ['class' => 'mt-4 text-center'])?>
        <?php
        }))
            ->titleAndPageTitle($this->listingDefinition->title);
    }

    private function SortableHeader(string $title, string $field) {
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
            'href' => ($this->listingDefinition->buildUrl)(array_merge($this->params->toArray(), ['sort' => $nextSort->toString()]))
        ]);
    }

    private function Thead(int $totalResults) {
        return Table::Thead(f\map(function($children) {
            return Table::Th($children);
        }, (function() use ($totalResults) {
            foreach ($this->listingDefinition->fields as $field) {
                yield $field->sortField
                    ? $this->SortableHeader($field->fieldName, $field->sortField)
                    : $field->fieldName;
            }

            yield h('div', 'Total Results: ' . $totalResults, ['class' => 'text-right']);
        })()));
    }

    private function TableBody() {
        $searchableEl = WrapNulls(function($children) {
            return SearchHighlight($this->params->search(), $children);
        });
        $el = WrapNulls();
        return !$this->items->isEmpty() ? f\map(function($item) use ($searchableEl, $el) {
            return Table::Tr((function() use ($item, $searchableEl, $el) {
                foreach ($this->listingDefinition->fields as $field) {
                    $children = ($field->access)($item);
                    yield Table::Td($field->searchable ? $searchableEl($children) : $el($children));
                }

                // actions
                yield Table::Td(h('div', [], ['class' => 'space-x-2 flex justify-end']));
            })());
        }, $this->items) : Table::Tr([Table::Td('No Results', ['class' => 'text-center text-gray-400', 'colspan' => 6])]);
    }

    private function Pagination(int $page, int $pageSize, int $maxResults) {
        return Pagination::fromTotalResults($maxResults, $page, $pageSize, function(ItemPage $itemPage) {
            return h(($itemPage->disabled() || $itemPage->selected()) ? 'span' : 'a', $itemPage->title(), [
                'class' => [
                    'px-3 py-2',
                    $itemPage->selected() ? 'text-pink-400 cursor-default' : null,
                    $itemPage->disabled() ? 'text-blue-200 cursor-default' : null,
                    !$itemPage->selected() && !$itemPage->disabled() ? 'text-blue-400 hover:text-blue-500 underline cursor-pointer' : null],
                'href' => ($this->listingDefinition->buildUrl)(array_merge($this->params->toArray(), ['page' => $itemPage->page()]))
            ]);
        }, ['class' => 'inline-flex divide-x divide-gray-200 text-center justify-center bg-white rounded-md shadow']);
    }
}
