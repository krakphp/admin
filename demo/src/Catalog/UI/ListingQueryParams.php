<?php

namespace Demo\App\Catalog\UI;

use Symfony\Component\HttpFoundation\Request;

final class ListingQueryParams
{
    private $search;
    private $page;
    private $pageSize;

    public function __construct(?string $search, int $page, int $pageSize) {
        $this->search = $search;
        $this->page = $page;
        $this->pageSize = $pageSize;
    }

    public static function fromRequest(Request $req): self {
        return new self(
            $req->query->get('search', null),
            $req->query->getInt('page', 1),
            $req->query->getInt('pageSize', 25)
        );
    }

    public function search(): ?string {
        return $this->search;
    }

    public function page(): int {
        return $this->page;
    }

    public function pageSize(): int {
        return $this->pageSize;
    }

    public function withPage(int $page): self {
        $self = clone $this;
        $self->page = $page;
        return $self;
    }

    public function toArray(): array {
        return ['page' => $this->page(), 'pageSize' => $this->pageSize, 'search' => $this->search];
    }
}
