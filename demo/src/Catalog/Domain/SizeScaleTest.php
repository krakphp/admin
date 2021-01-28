<?php

namespace Demo\App\Catalog\Domain;

use Krak\Fun\{f, c};

final class SizeScaleTest extends \PHPUnit\Framework\TestCase
{
    /** @var SizeScale */
    private $sizeScale;
    /** @var ?\Throwable */
    private $exception;

    /** @test */
    public function create_from_sizes() {
        $this->when_a_size_scale_is(function() { return new SizeScale('Test', ['1', '2']); });
        $this->then_size_scale_name_and_sizes_match('Test', ['1', '2']);
    }

    /** @test */
    public function update_sizes() {
        $this->given_a_draft_size_scale('Test', ['1', '2']);
        $this->when_a_size_scale_is(function(SizeScale $sizeScale) {
            $sizeScale->update('Updated', ['2', '3']);
        });
        $this->then_size_scale_name_and_sizes_match('Updated', ['2', '3']);
    }

    /** @test */
    public function does_not_allow_updating_sizes_when_not_in_draft() {
        $this->given_a_published_size_scale('Test', ['1']);
        $this->when_a_size_scale_is(function(SizeScale $sizeScale) {
            $sizeScale->update('Test', ['1', '2']);
        }, true);
        $this->then_an_exception_is_thrown('Cannot update sizes for published size scales.');
    }

    /** @test */
    public function allow_updating_name_when_published_regardless_of_size_order() {
        $this->given_a_published_size_scale('Test', ['1', '2']);
        $this->when_a_size_scale_is(function(SizeScale $sizeScale) {
            $sizeScale->update('Updated', ['2', '1']);
        });
        $this->then_size_scale_name_and_sizes_match('Updated', ['1', '2']);
    }

    /** @test */
    public function can_only_publish_within_draft_state() {
        $this->given_a_published_size_scale('Test', ['1']);
        $this->when_a_size_scale_is(function(SizeScale $sizeScale) {
            $sizeScale->publish();
        }, true);
        $this->then_an_exception_is_thrown('Can only publish size scales in draft state.');
    }

    /** @test */
    public function can_publish_shows_true_on_draft() {
        $this->given_a_draft_size_scale('Test', []);
        $this->then_the_size_can_be_published(true);
    }

    /** @test */
    public function can_publish_shows_false_if_published() {
        $this->given_a_published_size_scale('Test', []);
        $this->then_the_size_can_be_published(false);
    }

    private function given_a_draft_size_scale(string $name, array $sizes) {
        $this->sizeScale = new SizeScale($name, $sizes);
    }

    private function given_a_published_size_scale($name, $sizes) {
        $this->given_a_draft_size_scale($name, $sizes);
        $this->sizeScale->publish();
    }

    private function when_a_size_scale_is(callable $fn, bool $catch = false) {
        try {
            $res = $fn($this->sizeScale);
            if ($res) {
                $this->sizeScale = $res;
            }
        } catch (\Throwable $e) {
            if (!$catch) {
                throw $e;
            }
            $this->exception = $e;
        }
    }

    private function then_size_scale_name_and_sizes_match(string $name, array $sizes) {
        $this->assertEquals($name, $this->sizeScale->name());
        $this->assertEquals($sizes, f\toArray(f\arrayMap(c\method('size'), $this->sizeScale->sizes())));
    }

    private function then_an_exception_is_thrown(string $message) {
        $this->assertNotNull($this->exception, 'Exception should have been thrown.');
        $this->assertEquals($message, $this->exception->getMessage());
    }

    private function then_the_size_can_be_published(bool $published) {
        $this->assertEquals($published, $this->sizeScale->canPublish());
    }
}
