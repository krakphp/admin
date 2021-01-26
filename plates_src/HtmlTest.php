<?php

namespace League\Plates;

final class HtmlTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function renders_key_value_pairs_into_string_for_html() {
        $this->assertEquals(
            'a="1" b="1"',
            (string) attrs(['a' => 1, 'b' => 1])
        );
    }

    /** @test */
    public function merges_and_renders_multiple_attr_maps_preventing_duplicates() {
        $this->assertEquals(
            'a="1" b="2"',
            (string) attrs(['a' => 1, 'b' => 1], ['b' => 2])
        );
    }

    /** @test */
    public function supports_falsey_and_true_attributes() {
        $this->assertEquals(
            'b',
            (string) attrs(['a' => false, 'c' => null, 'b' => true])
        );
    }

    /**
     * @test
     * @dataProvider class_attribute_examples
     */
    public function supports_special_merging_semantics_for_class_attribute(array $attrMaps, string $expectedRender) {
        $this->assertEquals($expectedRender, (string) attrs(...$attrMaps));
    }

    public function class_attribute_examples() {
        yield 'standard appending' => [
            'attrMaps' => [
                ['class' => 'mt-1'],
                ['class' => 'mx-1'],
            ],
            'expectedRender' => 'class="mt-1 mx-1"'
        ];
        yield 'appending as classNames' => [
            'attrMaps' => [
                ['class' => 'mt-1'],
                ['class' => classNames('mx-1')]
            ],
            'expectedRender' => 'class="mt-1 mx-1"'
        ];
        yield 'replacing' => [
            'attrMaps' => [
                ['class' => 'mt-1 mx-1'],
                ['class' => classNames('mx-2')->replace()]
            ],
            'expectedRender' => 'class="mx-2"'
        ];
        yield 'replacing then appending' => [
            'attrMaps' => [
                ['class' => 'mt-1 mx-1'],
                ['class' => classNames('mx-2')->replace()],
                ['class' => 'mt-2']
            ],
            'expectedRender' => 'class="mx-2 mt-2"'
        ];
        yield 'append with string and list' => [
            'attrMaps' => [
                ['class' => 'mt-1 mx-1'],
                ['class' => classNames(['mx-2'])],
            ],
            'expectedRender' => 'class="mt-1 mx-1 mx-2"'
        ];
        yield 'falsey values in list are excluded' => [
            'attrMaps' => [
                ['class' => classNames(['mx-2', null, false, 'my-2'])],
            ],
            'expectedRender' => 'class="mx-2 my-2"'
        ];
        yield 'replace and append with string, list, and map' => [
            'attrMaps' => [
                ['class' => 'mt-1 mx-1'],
                ['class' => classNames(['mx-2'])],
                ['class' => classNames(['mt-1' => false, 'py-1' => true])],
            ],
            'expectedRender' => 'class="mx-1 mx-2 py-1"'
        ];
        yield 'string to map merging with additional spaces' => [
            'attrMaps' => [
                ['class' => 'mt-1    mx-1  mb-1'],
                ['class' => classNames(['mt-1' => false, 'mx-1' => false])],
            ],
            'expectedRender' => 'class="mb-1"'
        ];
        yield 'filters out empty class name sets' => [
            'attrMaps' => [
                ['class' => classNames([], 'mx-1', '', 'mt-1')],
            ],
            'expectedRender' => 'class="mx-1 mt-1"'
        ];
    }

    /** @test */
    public function empty_html_components() {
        $this->assertEquals('<div/>', (string) h('div'));
    }

    /** @test */
    public function html_components_with_children() {
        $this->assertEquals('<div>abc</div>', (string) h('div', 'abc'));
    }

    /** @test */
    public function html_components_with_attributes() {
        $this->assertEquals('<div a="1"/>', (string) h('div', null, ['a' => 1]));
    }

    /** @test */
    public function html_components_with_empty_attributes() {
        $this->assertEquals('<div/>', (string) h('div', null, ['a' => null]));
    }
}
