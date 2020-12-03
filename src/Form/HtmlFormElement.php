<?php

namespace Krak\Admin\Form;

abstract class HtmlFormElement
{
    protected $label;
    protected $fieldName;
    protected $value;

    protected function __construct(string $label, string $fieldName, ?string $value = null) {
        $this->label = $label;
        $this->fieldName = $fieldName;
        $this->value = $value;
    }

    public static function text(string $label, string $fieldName, ?string $value = null): HtmlFormTextElement {
        return new HtmlFormTextElement($label, $fieldName, $value);
    }

    /** @param HtmlFormSelectElementOption[] $options */
    public static function select(string $label, string $fieldName, array $options, ?string $value = null): HtmlFormSelectElement {
        return new HtmlFormSelectElement($label, $fieldName, $options, $value);
    }
}

final class HtmlFormTextElement extends HtmlFormElement {}
final class HtmlFormSelectElement extends HtmlFormElement {
    /** @var HtmlFormSelectElementOption[] */
    private $options;

    public function __construct(string $label, string $fieldName, array $options, ?string $value = null) {
        parent::__construct($label, $fieldName, $value);
        $this->options = $options;
    }

    /** @return HtmlFormSelectElementOption[] */
    public function options(): array {
        return $this->options;
    }
}

final class HtmlFormSelectElementOption {
    private $name;
    private $value;

    public function __construct(string $name, ?string $value) {
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): string {
        return $this->name;
    }

    public function value(): string {
        return $this->value;
    }
}

function option(string $name, string $value): HtmlFormSelectElementOption {
    return new HtmlFormSelectElementOption($name, $value);
}
