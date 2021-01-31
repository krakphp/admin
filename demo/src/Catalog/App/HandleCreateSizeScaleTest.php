<?php

namespace Demo\App\Catalog\App;

use Demo\App\AppKernel;
use Demo\App\Catalog\Domain\CreateSizeScale;
use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\Domain\SizeScaleTest;
use Demo\App\Test\KernelTestCase;
use Doctrine\Common\Collections\Criteria;
use function Krak\Fun\{head};

final class HandleCreateSizeScaleTest extends KernelTestCase
{
    /** @test */
    public function can_create_size_scale() {
        $this->when_a_size_scale_is_created(new CreateSizeScale('Test', [1,2,3]));
        $this->then_the_created_size_scale_matches([
            SizeScaleTest::matchSizeScaleName('Test'),
            SizeScaleTest::matchSizeScaleSizes([1,2,3]),
            self::matchSizeScaleRootVersionIdIsNonEmptyString(),
        ]);
    }

    private function when_a_size_scale_is_created(CreateSizeScale $command) {
        /** @var HandleCreateSizeScale $handle */
        $handle = self::$container->get(HandleCreateSizeScale::class);
        $handle($command);
    }

    private function then_the_created_size_scale_matches(array $matches) {
        /** @var SizeScaleRepository $sizeScaleRepo */
        $sizeScaleRepo = self::$container->get(SizeScaleRepository::class);
        /** @var SizeScale $sizeScale */
        $sizeScale = head($sizeScaleRepo->search(new Criteria()));
        foreach ($matches as $match) $match($sizeScale);
    }

    public static function matchSizeScaleRootVersionIdIsNonEmptyString() {
        return function(SizeScale $sizeScale) {
            self::assertIsString($sizeScale->rootVersionId());
            self::assertNotEmpty($sizeScale->rootVersionId());
        };
    }
}
