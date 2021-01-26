<?php

namespace League\Plates\Extension\Pagination;

final class ItemPage
{
    private $title;
    private $page;
    private $selected;

    public function __construct(string $title, ?int $page, bool $selected = false) {
        $this->title = $title;
        $this->page = $page;
        $this->selected = $selected;
    }

    public function title(): string {
        return $this->title;
    }

    public function page(): ?int {
        return $this->page;
    }

    public function selected(): bool {
        return $this->selected;
    }

    public function disabled(): bool {
        return $this->page === null;
    }
}
