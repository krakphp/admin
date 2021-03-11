<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\Tests\SizeScaleSteps;
use Demo\App\Catalog\Domain\DeleteSizeScale;
use Demo\App\Catalog\Domain\SizeScaleTest;
use Demo\App\Test\KernelTestCase;

final class HandleDeleteSizeScaleTest extends KernelTestCase
{
    private $sizeScaleSteps;

    protected function setUp(): void {
        parent::setUp();
        $this->sizeScaleSteps = SizeScaleSteps::fromContainer(self::$container);
    }

    /** @test */
    public function can_delete_a_draft_size_scale() {
        $this->sizeScaleSteps->given_the_following_size_scales([
            'test' => SizeScaleTest::draftSizeScale('Test', ['1'])
        ]);
        $this->when_the_size_scale_is_deleted(new DeleteSizeScale($this->sizeScaleSteps->sizeScaleId('test')));
        $this->sizeScaleSteps->then_the_total_number_of_size_scales_is(0);
    }

    /** @test */
    public function throws_if_size_scale_cannot_be_deleted() {
        $this->expectExceptionMessage('Only draft size scales can be deleted.');

        $this->sizeScaleSteps->given_the_following_size_scales([
            'test' => SizeScaleTest::publishedSizeScale('Test', ['1']),
        ]);
        $this->when_the_size_scale_is_deleted(new DeleteSizeScale($this->sizeScaleSteps->sizeScaleId('test')));
    }

    private function when_the_size_scale_is_deleted(DeleteSizeScale $command) {
        self::$container->get(HandleDeleteSizeScale::class)($command);
    }
}
