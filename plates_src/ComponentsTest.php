<?php

namespace League\Plates;

final class ComponentsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider provide_possible_component_types
     */
    public function wrapping_and_rendering_components($component, string $expectedRender) {
        $this->assertEquals($expectedRender, (string) p($component));
    }

    public function provide_possible_component_types() {
        yield 'string' => ['a', 'a'];
        yield 'null' => [null, ''];
        yield 'arrays' => [['a', ['b', 'c']], 'abc'];
        yield 'iterable' => [new \ArrayIterator(['a', 'b']), 'ab'];
        yield 'callable' => [function() { echo 'a'; }, 'a'];
        yield '__toString' => [new class() { public function __toString() { return 'a'; }}, 'a'];
        yield 'Component' => [new class() extends Component { public function __invoke(): void { echo 'a'; }}, 'a'];
    }

    /** @test */
    public function accessing_context_outside_of_render_throws_exception() {
        $this->expectExceptionMessage('No component contexts have been rendered, cannot access global context.');
        context('id');
    }

    /** @test */
    public function context_static_values_remain_static_across_renders() {
        $context = new ComponentContext();
        $expectedObject = new class() {};
        $context->addStatic('id', $expectedObject);

        $objectsFromContext = [];
        foreach (range(1, 2) as $i) {
            $context->render(function() use (&$objectsFromContext) {
                $objectsFromContext[] = context('id');
            });
        }

        $this->assertSame($expectedObject, $objectsFromContext[0]);
        $this->assertSame($expectedObject, $objectsFromContext[1]);
    }

    /** @test */
    public function context_factories_are_reset_per_render() {
        $context = new ComponentContext();
        $context->add('id', function() { return new class() {};});

        $objectsFromContext = [];
        foreach (range(1, 2) as $i) {
            $context->render(function() use (&$objectsFromContext) {
                $objectsFromContext[] = context('id');
            });
        }

        $this->assertNotSame($objectsFromContext[0], $objectsFromContext[1]);
    }
}
