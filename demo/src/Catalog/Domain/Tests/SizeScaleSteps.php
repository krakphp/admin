<?php

namespace Demo\App\Catalog\Domain\Tests;

use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Doctrine\Common\Collections\Criteria;
use Psr\Container\ContainerInterface;
use function PHPUnit\Framework\assertEquals;

final class SizeScaleSteps
{
    private $sizeScaleRepo;
    private $createdSizeScaleIds = [];

    private function __construct(SizeScaleRepository $sizeScaleRepo) {
        $this->sizeScaleRepo = $sizeScaleRepo;
    }

    public static function fromContainer(ContainerInterface $container): self {
        return new self(
            $container->get(SizeScaleRepository::class)
        );
    }

    /** @param SizeScale[] $sizeScales */
    public function given_the_following_size_scales(iterable $sizeScales) {
        $this->createdSizeScaleIds = [];
        foreach ($sizeScales as $key => $sizeScale) {
            $this->sizeScaleRepo->save($sizeScale);
            $this->createdSizeScaleIds[$key] = $sizeScale->id();
        }
    }

    public function sizeScaleId(string $key): ?int {
        return $this->createdSizeScaleIds[$key] ?? null;
    }

    public function then_the_total_number_of_size_scales_is(int $totalSizeScales) {
        assertEquals($totalSizeScales, count($this->sizeScaleRepo->search(new Criteria())));
    }

    public function then_the_size_scale_matches(int $sizeScaleId, iterable $matches) {
        $sizeScale = $this->sizeScaleRepo->get($sizeScaleId);
        foreach ($matches as $match) $match($sizeScale);
    }
}
