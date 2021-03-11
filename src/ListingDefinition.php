<?php

namespace Krak\Admin;

use function League\Plates\Extension\Symfony\path;

final class ListingDefinition
{
    public $title;
    /** @var ListingDefinitionField[] */
    public $fields;
    public $buildUrl;

    public function __construct(string $title, array $fields, callable $buildUrl) {
        $this->title = $title;
        $this->fields = $fields;
        $this->buildUrl = $buildUrl;
    }

    public static function symfonyBuildUrl(string $routeName) {
        return function(array $params) use ($routeName) {
            return path($routeName, $params);
        };
    }

    public function hasPageActions(): bool {
        return false;
    }

    public function hasItemActions(): bool {
        return false;
    }
}

final class ListingDefinitionField
{
    public $fieldName;
    public $access;
    public $componentFactory;
    public $searchable = false;
    public $sortField = null;

    public function __construct(string $fieldName, callable $access) {
        $this->fieldName = $fieldName;
        $this->access = $access;
    }

    public function access(callable $access): self {
        $self = clone $this;
        $self->access = $access;
        return $self;
    }

    public function searchable(bool $searchable = true): self {
        $self = clone $this;
        $self->searchable = $searchable;
        return $self;
    }

    public function sortable(?string $sortField = null): self {
        $self = clone $this;
        $self->sortField = $sortField;
        return $self;
    }

    public function componentFactory(string $componentFactory): self {
        $self = clone $this;
        $self->componentFactory = $componentFactory;
        return $self;
    }
}
