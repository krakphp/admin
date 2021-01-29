<?php

namespace Demo\App\Catalog\Domain;

use Krak\Fun\{f, c};
use function Krak\Effects\handleEffects;

final class SizeScaleTest extends \PHPUnit\Framework\TestCase
{
    /** @var SizeScale */
    private $sizeScale;
    /** @var ?\Throwable */
    private $exception;

    /** @test */
    public function create_from_sizes() {
        $this->when_a_size_scale_is(function() {
            return handleEffects(SizeScale::create(new CreateSizeScale('Test', ['1'])), [
                GenerateRootVersionId::class => function() { return new GeneratedRootVersionId('1234'); }
            ]);
        });
        $this->then_size_scale_matches($this->sizeScale(), [
            $this->matchSizeScaleName('Test'),
            $this->matchSizeScaleSizes(['1']),
            $this->matchRootVersionId('1234'),
        ]);
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

    public function can_create_new_version() {
        $this->given_a_published_size_scale('A', ['1', '2']);
        $this->when_a_size_scale_is(function() {

        });
//        $this->then_size_scale_name_and_sizes_match()
    }

    public function only_allows_one_new_version_to_be_created() {
        
    }

    public function can_only_make_new_version_if_published() {
        
    }

    public function publishing_a_new_version_archives_previous_version() {
        
    }

    public function can_only_archive_published_size_scales() {
        
    }

    public function cannot_archive_published_size_scales_with_newer_draft_version() {

    }

    public function only_draft_size_scales_can_be_deleted() {
        
    }
    
    

    private function given_a_draft_size_scale(string $name, array $sizes, string $rootVersionId = '1234') {
        $this->sizeScale = self::draftSizeScale($name, $sizes, $rootVersionId);
    }

    private function given_a_published_size_scale($name, $sizes, ?string $rootVersionId = '1234') {
        $this->sizeScale = self::publishedSizeScale($name, $sizes, $rootVersionId);
    }

    public static function draftSizeScale(string $name, array $sizes, string $rootVersionId = '1234'): SizeScale {
        return handleEffects(SizeScale::create(new CreateSizeScale($name, $sizes)), [
            GenerateRootVersionId::class => function() use ($rootVersionId) { return new GeneratedRootVersionId($rootVersionId); }
        ]);
    }

    public static function publishedSizeScale(string $name, array $sizes, string $rootVersionId = '1234'): SizeScale {
        $sizeScale = self::draftSizeScale($name, $sizes, $rootVersionId);
        $sizeScale->publish();
        return $sizeScale;
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
        $this->then_size_scale_matches($this->sizeScale, [
            $this->matchSizeScaleName($name),
            $this->matchSizeScaleSizes($sizes)
        ]);
    }

    private function then_size_scale_matches(SizeScale $sizeScale, array $matches) {
        foreach ($matches as $match) {
            $match($sizeScale);
        }
    }

    private function sizeScale(): SizeScale {
        return $this->sizeScale;
    }

    private function then_an_exception_is_thrown(string $message) {
        $this->assertNotNull($this->exception, 'Exception should have been thrown.');
        $this->assertEquals($message, $this->exception->getMessage());
    }

    private function then_the_size_can_be_published(bool $published) {
        $this->assertEquals($published, $this->sizeScale->canPublish());
    }

    private function matchSizeScaleName(string $name): callable {
        return function(SizeScale $sizeScale) use ($name) {
            $this->assertEquals($name, $sizeScale->name());
        };
    }

    private function matchSizeScaleSizes(array $sizes): callable {
        return function(SizeScale $sizeScale) use ($sizes) {
            $this->assertEquals($sizes, f\toArray(f\arrayMap(c\method('size'), $this->sizeScale->sizes())));
        };
    }

    private function matchRootVersionId(string $rootVersionId) {
        return function(SizeScale $sizeScale) use ($rootVersionId) {
            $this->assertEquals($rootVersionId, $sizeScale->rootVersionId());
        };
    }
}
