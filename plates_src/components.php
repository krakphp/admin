<?php

namespace League\Plates;

final class ComponentContext
{
    /** @var ?ComponentContext */
    private static $instance;
    // factories are reset per render
    private $factories = [];
    // cache result of the factories per render
    private $cachedFactories = [];
    // statics are registered once and stay static across all renders
    private $statics = [];

    public static function self(): self {
        if (self::$instance) {
            return self::$instance;
        }

        throw new \RuntimeException('No component contexts have been rendered, cannot access global context.');
    }

    public function add(string $id, callable $factory, ?string $alt = null): self {
        $id = $this->id($id, $alt);
        $this->factories[$id] = $factory;
        unset($this->statics[$id]);
        return $this;
    }

    public function addStatic(string $id, $value, ?string $alt = null): self {
        $id = $this->id($id, $alt);
        $this->statics[$id] = $value;
        unset($this->factories[$id]);
        return $this;
    }

    public function get(string $id, ?string $alt = null) {
        $id = $this->id($id, $alt);
        if (!array_key_exists($id, $this->factories) && !array_key_exists($id, $this->statics)) {
            throw new \RuntimeException("Entry $id not found in ComponentContext.");
        }

        if (array_key_exists($id, $this->cachedFactories)) {
            return $this->cachedFactories[$id];
        }
        if (array_key_exists($id, $this->factories)) {
            return $this->cachedFactories[$id] = ($this->factories[$id])($this);
        }

        return $this->statics[$id];
    }

    private function id(string $id, ?string $alt = null): string {
        return $alt === null ? $id : $id . '.' . $alt;
    }

    public function render($component): string {
        $this->cachedFactories = [];
        self::$instance = $this;
        try {
            return (string) p($component);
        } finally {
            self::$instance = null;
        }
    }
}

function context(string $id, ?string $alt = null) {
    return ComponentContext::self()->get($id, $alt);
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
    $canBeEchoed = is_string($component)
        || is_int($component)
        || is_null($component)
        || (is_object($component) && method_exists($component, '__toString'));
    if ($canBeEchoed) {
        return new EchoComponent($component);
    }
    if (is_callable($component)) {
        return new FunctionComponent($component);
    }
    if (is_array($component)) {
        return new FunctionComponent(function() use ($component) {
            foreach ($component as $c) {
                echo p($c);
            }
        });
    }

    throw new \RuntimeException('Could not convert component into an instance of Component.');
}
