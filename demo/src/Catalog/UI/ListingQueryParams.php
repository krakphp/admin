<?php

namespace Demo\App\Catalog\UI;

use Symfony\Component\HttpFoundation\Request;

final class ListingQueryParams
{
    private $search;
    private $page;
    private $pageSize;
    private $sort;

    public function __construct(?string $search, int $page, int $pageSize, ?string $sort) {
        $this->search = $search;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->sort = $sort;
    }

    public static function fromRequest(Request $req): self {
        return new self(
            $req->query->get('search', null),
            $req->query->getInt('page', 1),
            $req->query->getInt('pageSize', 25),
            $req->query->get('sort', null)
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

    public function sort(): ?string {
        return $this->sort;
    }

    public function sortTuple(): SortTuple {
        return SortTuple::fromString($this->sort);
    }

    public function toArray(): array {
        return ['page' => $this->page(), 'pageSize' => $this->pageSize, 'search' => $this->search, 'sort' => $this->sort];
    }
}

final class SortTuple {
    const ASC = 'asc';
    const DESC = 'desc';

    private $field;
    private $dir;

    private function __construct(?string $field, ?string $dir) {
        $this->field = $field;
        $this->dir = $dir;
    }

    public static function new(string $field) {
        return new self($field, self::ASC);
    }

    public static function fromString(?string $sort) {
        if (!$sort) {
            return new self(null, null);
        }

        [$sort, $dir] = explode(':', $sort);
        if ($dir !== self::ASC && $dir !== self::DESC) {
            throw new \InvalidArgumentException('Expecting sort direction of asc or desc, got ' . $dir);
        }
        return new self($sort, $dir);
    }

    public function toString(): ?string {
        return $this->field && $this->dir ? implode(':', [$this->field, $this->dir]) : null;
    }

    public function field(): ?string {
        return $this->field;
    }

    public function dir(): ?string {
        return $this->dir;
    }

    public function isAsc(): bool {
        return $this->dir === self::ASC;
    }

    /** Cycle from asc -> desc -> null */
    public function cycle(): self {
        $self = clone $this;
        if ($this->dir === self::ASC) {
            $self->dir = self::DESC;
        } else if ($this->dir === self::DESC) {
            $self->dir = null;
        } else {
            $self->dir = self::ASC;
        }
        return $self;
    }
}
