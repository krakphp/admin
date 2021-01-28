<?php

namespace Krak\Admin\Templates\Typography;

use League\Plates\Component;
use function League\Plates\{classNames, Extension\Symfony\csrfToken, p, h, attrs};

function PageTitle($title, ...$attrs) {
    return h('h1', $title, ['class' => 'font-medium text-2xl text-gray-900 mb-4'], ...$attrs);
}

function TextLink($content, string $href, ...$attrs) {
    return h('a', $content, ['href' => $href, 'class' => 'text-blue-400 hover:text-blue-500 underline'], ...$attrs);
}

final class FormLink extends Component
{
    private $action;
    private $method;
    private $children;
    private $attrs;
    private $tokenName = 'token';

    public function __construct(string $action, string $method, $children, ...$attrs) {
        $this->action = $action;
        $this->method = $method;
        $this->children = $children;
        $this->attrs = $attrs;
    }

    public static function post(string $action, $children, ...$attrs) {
        return new self($action, 'post', $children, ...$attrs);
    }

    public static function delete(string $action, $children, ...$attrs) {
        return new self($action, 'delete', $children, ...$attrs);
    }

    public static function patch(string $action, $children, ...$attrs) {
        return new self($action, 'patch', $children, ...$attrs);
    }

    public function tokenName(string $tokenName): self {
        $this->tokenName = $tokenName; return $this;
    }

    public function __invoke(): void {
    ?>
      <form <?=attrs(['method' => 'post', 'action' => $this->action], ...$this->attrs)?>>
        <input type="hidden" name="_method" value="<?=$this->method?>"/>
        <input type="hidden" name="_token" value="<?=csrfToken($this->tokenName)?>"/>
        <?=p($this->children)?>
      </form>
    <?php
    }
}

function Button($title, string $type = 'info', ...$attrs) {
    switch ($type) {
      case 'success': $classes = 'bg-green-400 hover:bg-green-500'; break;
      case 'info':
      default: $classes = 'bg-blue-500 hover:bg-blue-600'; break;
    }
    return h('button', $title, ['class' => "rounded-md hover:underline text-sm text-white px-4 py-2 $classes"], ...$attrs);
}

function ButtonLink($title, string $href, string $type = 'info', ...$attrs) {
    return Button($title, $type, ['href' => $href, 'class' => 'inline-block'], ...$attrs)->nodeName('a');
}

function Card($children, ...$attrs) {
    return h('div', $children, ['class' => 'bg-white sm:rounded-lg shadow'], ...$attrs);
}

function DefinitionList($items, ...$attrs) {
    return h('dl', $items, ['class' => 'divide-y divide-gray-200'], ...$attrs);
}

function DefinitionListItem($term, $definition) {
    return h('div', [
        h('dt', $term, ['class' => 'text-pink-300']),
        h('dd', $definition, ['class' => 'col-span-2'])
    ], ['class' => 'grid grid-cols-3 gap-4 p-4']);
}
