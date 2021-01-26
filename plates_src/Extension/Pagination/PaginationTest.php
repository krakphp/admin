<?php

namespace League\Plates\Extension\Pagination;

use PHPUnit\Framework\TestCase;
use function League\Plates\h;

final class PaginationTest extends TestCase
{
    /**
     * @test
     * @dataProvider provide_pagination_links
     */
    public function builds_pagination_links(array $pages, int $totalResults, int $page, int $pageSize) {
        $this->assertEquals(
            (string) h('div', $pages),
            (string) Pagination::fromTotalResults($totalResults, $page, $pageSize, function(ItemPage $page) {
                return h('span', $page->title(), ['page' => $page->page(), 'selected' => $page->selected(), 'disabled' => $page->disabled()]);
            })
        );
    }

    public function provide_pagination_links() {
        yield '10,1,5' => [
            'pages' => [
                $this->disabledPage('First', 1),
                $this->disabledPage('Previous'),
                $this->selectedPage('1', 1),
                $this->page('2', 2),
                $this->page('Next', 2),
                $this->page('Last', 2),
            ],
            'totalResults' => 10,
            'page' => 1,
            'pageSize' => 5,
        ];
        yield '10,2,5' => [
            'pages' => [
                $this->page('First', 1),
                $this->page('Previous', 1),
                $this->page('1', 1),
                $this->selectedPage('2', 2),
                $this->disabledPage('Next'),
                $this->disabledPage('Last'),
            ],
            'totalResults' => 10,
            'page' => 2,
            'pageSize' => 5,
        ];
        yield '10,1,2' => [
            'pages' => [
                $this->disabledPage('First'),
                $this->disabledPage('Previous'),
                $this->selectedPage('1', 1),
                $this->page('2', 2),
                $this->page('3', 3),
                $this->page('4', 4),
                $this->page('5', 5),
                $this->page('Next', 2),
                $this->page('Last', 5),
            ],
            'totalResults' => 10,
            'page' => 1,
            'pageSize' => 2,
        ];
        yield '10,2,2' => [
            'pages' => [
                $this->page('First', 1),
                $this->page('Previous', 1),
                $this->page('1', 1),
                $this->selectedPage('2', 2),
                $this->page('3', 3),
                $this->page('4', 4),
                $this->page('5', 5),
                $this->page('Next', 3),
                $this->page('Last', 5),
            ],
            'totalResults' => 10,
            'page' => 2,
            'pageSize' => 2,
        ];
        yield '10,3,2' => [
            'pages' => [
                $this->page('First', 1),
                $this->page('Previous', 2),
                $this->page('1', 1),
                $this->page('2', 2),
                $this->selectedPage('3', 3),
                $this->page('4', 4),
                $this->page('5', 5),
                $this->page('Next', 4),
                $this->page('Last', 5),
            ],
            'totalResults' => 10,
            'page' => 3,
            'pageSize' => 2,
        ];
        yield '10,5,1' => [
            'pages' => [
                $this->page('First', 1),
                $this->page('Previous', 4),
                $this->page('3', 3),
                $this->page('4', 4),
                $this->selectedPage('5', 5),
                $this->page('6', 6),
                $this->page('7', 7),
                $this->page('Next', 6),
                $this->page('Last', 10),
            ],
            'totalResults' => 10,
            'page' => 5,
            'pageSize' => 1,
        ];
    }

    private function page(string $title, int $page) {
        return h('span', $title, ['page' => $page]);
    }

    private function selectedPage(string $title, int $page) {
        return h('span', $title, ['page' => $page, 'selected' => true]);
    }

    private function disabledPage(string $title) {
        return h('span', $title, ['disabled' => true]);
    }
}
