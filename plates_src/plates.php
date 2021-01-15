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

/** @param callable|string|object $component */
function p($component, string $scope = ScopedRegistry::SCOPE_DEFAULT) {
    if (is_string($component) || (is_object($component) && method_exists($component, '__toString'))) {
        return (string) $component; // this check is just for API flexibility when you want to accept a string or callable
    }

    $cur_level = ob_get_level();

    try {
        ob_start();
        $component(ScopedRegistry::self($scope));
        return ob_get_clean();
    } catch (\Throwable $e) {}

    // clean the ob stack
    while (ob_get_level() > $cur_level) {
        ob_end_clean();
    }

    throw $e;
}
