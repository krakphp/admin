<?php

namespace League\Plates;

final class ContextRegistry
{
    private static $instance;

    private $contextByName;
    private function __construct() {}

    public static function self(): self {
        return self::$instance ?? self::$instance = new self();
    }

    public function get(string $className) {
        return $this->contextByName[$className] ?? null;
    }

    public function set($context, string $className = 'default') {
        $this->contextByName[$className] = $context;
    }
}

final class ScopedRegistry
{
    const SCOPE_DEFAULT = 'default';

    private static $instancesByScope;
    private $data = [];

    private function __construct() {}

    public static function self(string $scope = ScopedRegistry::SCOPE_DEFAULT) {
        return self::$instancesByScope[$scope] ?? self::$instancesByScope[$scope] = new self();
    }

    public function get(string $classNameOrKey) {
        return $this->data[$classNameOrKey] ?? null;
    }

    public function set(string $key, $value): self {
        $this->data[$key] = $value;
        return $this;
    }
}

abstract class Component {
    abstract public function __invoke(): void;

    public function __toString() {
        return render($this);
    }
}

final class FunctionComponent extends Component {
    private $fn;

    public function __construct(callable $fn) {
        $this->fn = $fn;
    }

    public function __invoke(): void {
        ($this->fn)();
    }
}

final class EchoComponent extends Component {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function __invoke(): void {
        echo $this->value;
    }
}

function render(callable $component) {
    $cur_level = ob_get_level();

    try {
        ob_start();
        $component();
        return ob_get_clean();
    } catch (\Throwable $e) {}

    // clean the ob stack
    while (ob_get_level() > $cur_level) {
        ob_end_clean();
    }

    throw $e;
}

function p($component) {
    if ($component instanceof Component) {
        return $component;
    }
    if (is_string($component) || (is_object($component) && method_exists($component, '__toString'))) {
        return new EchoComponent($component);
    }
    if (is_callable($component)) {
        return new FunctionComponent($component);
    }

    throw new \RuntimeException('Could not convert component into an instance of Component.');
}
