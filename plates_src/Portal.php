<?php

namespace League\Plates;

final class Portal extends Component
{
    /** @var Component[] */
    private $components = [];

    public function append($component): self {
        $this->components[] = p($component);
        return $this;
    }

    public function set(string $name, $component): self {
        $this->components[$name] = $component;
        return $this;
    }

    public function prepend($component): self {
        array_unshift($this->components, p($component));
        return $this;
    }

    public function clear(): self {
        $this->components = [];
        return $this;
    }

    public function __invoke(): void {
        foreach ($this->components as $component) {
            echo $component;
        }
    }
}
