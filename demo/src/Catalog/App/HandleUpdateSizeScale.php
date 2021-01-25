<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\Domain\UpdateSizeScale;

final class HandleUpdateSizeScale
{
    private $sizeScaleRepo;

    public function __construct(SizeScaleRepository $sizeScaleRepo) {
        $this->sizeScaleRepo = $sizeScaleRepo;
    }

    public function __invoke(UpdateSizeScale $command): SizeScale {
        $sizeScale = $this->sizeScaleRepo->get($command->sizeScaleId());
        $sizeScale->updateFromCommand($command);
        $this->sizeScaleRepo->save($sizeScale);
        return $sizeScale;
    }
}
