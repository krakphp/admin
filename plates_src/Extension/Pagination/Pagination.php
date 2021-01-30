<?php

namespace League\Plates\Extension\Pagination;

use League\Plates\Component;
use function League\Plates\h;

final class Pagination extends Component
{
    private $totalResults;
    private $page;
    private $pageSize;
    private $render;
    private $attrs;
    private $includeFirstLast = true;
    private $includePrevNext = true;
    private $zeroIndexed = false;
    private $maxPagesToShow = 5;
    private $nodeName = 'div';

    public static function fromTotalResults(int $totalResults, int $page, int $pageSize, callable $render, ...$attrs): self {
        $self = new self();
        $self->totalResults = $totalResults;
        $self->page = $page;
        $self->pageSize = $pageSize;
        $self->render = $render;
        $self->attrs = $attrs;
        return $self;
    }

    public function includeFirstLast(bool $includeFirstLast): self {
        $this->includeFirstLast = $includeFirstLast; return $this;
    }

    public function includePrevNext(bool $includePrevNext): self {
        $this->includePrevNext = $includePrevNext; return $this;
    }

    public function zeroIndexed(bool $zeroIndexed): self {
        $this->zeroIndexed = $zeroIndexed; return $this;
    }

    public function maxPagesToShow(int $maxPagesToShow): self {
        $this->maxPagesToShow = $maxPagesToShow; return $this;
    }

    public function nodeName(string $nodeName): self {
        $this->nodeName = $nodeName; return $this;
    }

    public function __invoke(): void {
        echo h($this->nodeName, $this->listItems(), ...$this->attrs);
    }

    private function listItems(): iterable {
        if ($this->totalResults === 0) {
            return;
        }

        if ($this->includeFirstLast) {
            yield ($this->render)(new ItemPage('First', $this->page <= $this->firstPage() ? null : $this->firstPage()));
        }
        if ($this->includePrevNext) {
            yield ($this->render)(new ItemPage('Previous', $this->page <= $this->firstPage() ? null : $this->page - 1));
        }

        yield from array_map(function(int $page) {
            return ($this->render)(new ItemPage((string) $page, $page, $page === $this->page));
        }, $this->listValidPages());

        if ($this->includePrevNext) {
            yield ($this->render)(new ItemPage('Next', $this->page >= $this->lastPage() ? null : $this->page + 1));
        }
        if ($this->includeFirstLast) {
            yield ($this->render)(new ItemPage('Last', $this->page >= $this->lastPage() ? null : $this->lastPage()));
        }
    }

    private function listValidPages() {
        if ($this->maxPagesToShow <= 0) {
            return [];
        }
        if ($this->maxPagesToShow === 1) {
            return [$this->page];
        }

        $minPageToShow = max($this->firstPage(), $this->page - $this->maxPagesToShow + 1);
        $maxPageToShow = min($this->lastPage(), $this->page + $this->maxPagesToShow - 1);
        while (($maxPageToShow - $minPageToShow) > ($this->maxPagesToShow - 1)) {
            if (($maxPageToShow - $this->page) >= ($this->page - $minPageToShow)) {
                $maxPageToShow -= 1;
            } else {
                $minPageToShow += 1;
            }
        }

        return range($minPageToShow, $maxPageToShow);
    }


    private function firstPage(): int {
        return $this->zeroIndexed ? 0 : 1;
    }

    private function lastPage(): int {
        return ceil($this->totalResults / $this->pageSize) - ($this->zeroIndexed ? 1 : 0);
    }
}
