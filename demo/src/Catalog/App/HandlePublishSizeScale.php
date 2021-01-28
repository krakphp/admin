<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\PublishSizeScale;
use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;

final class HandlePublishSizeScale
{
    private $sizeScaleRepository;

    public function __construct(SizeScaleRepository $sizeScaleRepository) {
        $this->sizeScaleRepository = $sizeScaleRepository;
    }

    public function __invoke(PublishSizeScale $command): SizeScale {
        $sizeScale = $this->sizeScaleRepository->get($command->sizeScaleId());
        $sizeScale->publish();
        $this->sizeScaleRepository->save($sizeScale);
        return $sizeScale;
    }
}
